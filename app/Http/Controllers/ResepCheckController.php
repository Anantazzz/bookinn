<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResepCheckController extends Controller
{
    // Pastikan hanya resepsionis yang bisa akses
    public function __construct()
    {
        $this->middleware(['auth', 'role:resepsionis']);
    }
    
    //Tampilkan daftar reservasi hotel milik resepsionis.   
    public function index()
    {
        $hotelId = Auth::user()->hotel_id;

        $reservasis = Reservasi::whereHas('kamar', function($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->with('user', 'kamar.tipeKamar')
            ->orderBy('id', 'desc')
            ->get();

        $now = Carbon::now(); // server time, consistent with Carbon throughout

        foreach ($reservasis as $r) {
            // --- BUILD DATETIME dari tanggal + jam --- //
            // Pastikan field-nya memang ada: tanggal_checkin (date), jam_checkin (time)
            // Kalau jam_checkin/ jam_checkout nullable pakai fallback '00:00:00'
            $checkinDatetime = null;
            $checkoutDatetime = null;

            if (isset($r->tanggal_checkin)) {
                $jamCheckin = $r->jam_checkin ?? '00:00:00';
                $checkinDatetime = Carbon::parse($r->tanggal_checkin . ' ' . $jamCheckin);
            }

            if (isset($r->tanggal_checkout)) {
                $jamCheckout = $r->jam_checkout ?? '00:00:00';
                $checkoutDatetime = Carbon::parse($r->tanggal_checkout . ' ' . $jamCheckout);
            }

            // Hanya ubah tampilan status kalau status DB masih 'pending'
            // (atau jika kamu ingin auto-recover expired bookings, bisa ubah kebijakan)
            if ($r->status === 'pending') {
                if ($checkinDatetime && $now->lt($checkinDatetime)) {
                    // masih sebelum waktu check-in -> pending (tetap)
                    $r->status_for_display = 'pending';
                } elseif ($checkinDatetime && $checkoutDatetime && $now->between($checkinDatetime, $checkoutDatetime)) {
                    // antara checkin..checkout -> aktif
                    $r->status_for_display = 'aktif';
                } elseif ($checkoutDatetime && $now->gt($checkoutDatetime)) {
                    // sudah lewat checkout -> selesai
                    $r->status_for_display = 'selesai';
                } else {
                    // fallback
                    $r->status_for_display = $r->status;
                }
            } else {
                // jika DB berisi 'aktif'/'selesai'/'batal', kita tampilkan apa adanya (tidak menimpa)
                $r->status_for_display = $r->status;
            }
        }

        return view('resepsionis.check.index', compact('reservasis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,aktif,selesai,batal'
        ]);

        $reservasi = Reservasi::findOrFail($id);

        // update dan persist ke DB
        $reservasi->status = $request->status;
        $reservasi->save();

        return back()->with('success', 'Status reservasi berhasil diubah.');
    }
}

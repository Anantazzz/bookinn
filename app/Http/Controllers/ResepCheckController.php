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
    
    // Tampilkan daftar reservasi hotel milik resepsionis.   
    public function index()
    {
        $hotelId = Auth::user()->hotel_id;

        $reservasis = Reservasi::whereHas('kamar', function($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->with(['user', 'kamar.tipeKamar'])
            ->orderBy('id', 'desc')
            ->get();

        $now = Carbon::now();

        foreach ($reservasis as $r) {
            // Build datetime dari tanggal + jam
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

            // Simpan info apakah sudah waktunya checkin/checkout (untuk logika tombol di view)
            $r->can_checkin = false;
            $r->can_checkout = false;

            // PENTING: status_for_display SELALU mengikuti status di database
            // Jangan ubah tampilan status berdasarkan waktu!
            $r->status_for_display = $r->status;

            // Logika untuk tombol aksi
            if ($r->status === 'pending') {
                // Cek apakah sudah waktunya checkin (untuk munculkan tombol Check-in)
                if ($checkinDatetime && $now->gte($checkinDatetime)) {
                    $r->can_checkin = true;
                }
            } 
            elseif ($r->status === 'aktif') {
                // Jika sudah aktif, selalu bisa checkout (manual atau otomatis)
                $r->can_checkout = true;
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

        // Validasi tambahan: pastikan perubahan status logis
        if ($request->status === 'aktif' && $reservasi->status !== 'pending') {
            return back()->with('error', 'Hanya reservasi dengan status pending yang bisa di-checkin.');
        }

        if ($request->status === 'selesai' && $reservasi->status !== 'aktif') {
            return back()->with('error', 'Hanya reservasi dengan status aktif yang bisa di-checkout.');
        }

        // Update dan persist ke DB
        $reservasi->status = $request->status;
        $reservasi->save();

        return back()->with('success', 'Status reservasi berhasil diubah.');
    }
}
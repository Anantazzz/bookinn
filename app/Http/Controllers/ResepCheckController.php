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
    
    // Tampilkan daftar reservasi hotel milik resepsionis + search
    public function index(Request $request)
    {
        $hotelId = Auth::user()->hotel_id;
        $search = $request->input('search'); // ambil keyword dari form

        $reservasis = Reservasi::whereHas('kamar', function($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->when($search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->with(['user', 'kamar.tipeKamar'])
            ->orderBy('id', 'desc')
            ->get();

        $now = Carbon::now();

        foreach ($reservasis as $r) {
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

            $r->can_checkin = false;
            $r->can_checkout = false;
            $r->status_for_display = $r->status;

            if ($r->status === 'pending') {
                if ($checkinDatetime && $now->gte($checkinDatetime)) {
                    $r->can_checkin = true;
                }
            } 
            elseif ($r->status === 'aktif') {
                $r->can_checkout = true;
            }
        }

        return view('resepsionis.check.index', compact('reservasis', 'search'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,aktif,selesai,batal'
        ]);

        $reservasi = Reservasi::findOrFail($id);

        if ($request->status === 'aktif' && $reservasi->status !== 'pending') {
            return back()->with('error', 'Hanya reservasi dengan status pending yang bisa di-checkin.');
        }

        if ($request->status === 'selesai' && $reservasi->status !== 'aktif') {
            return back()->with('error', 'Hanya reservasi dengan status aktif yang bisa di-checkout.');
        }

        $reservasi->status = $request->status;
        $reservasi->save();

        return back()->with('success', 'Status reservasi berhasil diubah.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;

class ReservasiController extends Controller
{
     public function showForm($id)
    {
        $user = Auth::user(); // ambil data user yang login
        $kamar = Kamar::findOrFail($id); // ambil data kamar berdasarkan id

        return view('hotels.reservasi', compact('user', 'kamar'));
    }

   public function store(Request $request, $id)
{
    $user = Auth::user();
    $kamar = Kamar::findOrFail($id);

    $request->validate([
        'tanggal_checkin' => 'required|date',
        'jam_checkin' => 'required',
        'tanggal_checkout' => 'required|date|after_or_equal:tanggal_checkin',
        'jam_checkout' => 'required',
    ]);

    // Cek apakah user centang kasur tambahan
    $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0;

    // Hitung total harga
    $hargaKamar = $kamar->harga;
    $biayaKasur = $kasurTambahan ? 100000 : 0;
    $totalHarga = $hargaKamar + $biayaKasur;

    // Simpan data reservasi
    $reservasi = Reservasi::create([
        'user_id' => $user->id,
        'kamar_id' => $kamar->id,
        'tanggal_checkin' => $request->tanggal_checkin,
        'jam_checkin' => $request->jam_checkin,
        'tanggal_checkout' => $request->tanggal_checkout,
        'jam_checkout' => $request->jam_checkout,
        'status' => 'pending',
        'kasur_tambahan' => $kasurTambahan,
        'total_harga' => $totalHarga,
    ]);

    return redirect()->route('hotel.pembayaran', ['id' => $kamar->id])
                     ->with('success', 'Reservasi berhasil! Silakan lanjutkan ke pembayaran.'); 
    }   
} 
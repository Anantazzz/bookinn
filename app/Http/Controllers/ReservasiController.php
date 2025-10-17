<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;

class ReservasiController extends Controller
{
    public function showForm(Request $request, $id)
    {
        $user = Auth::user(); // ambil data user yang login
        $kamar = Kamar::findOrFail($id); // ambil data kamar berdasarkan id

        // Ambil tanggal dari query string (dari halaman detail hotel)
        $tanggalCheckin = $request->input('tanggal_checkin');
        $tanggalCheckout = $request->input('tanggal_checkout');

        return view('hotels.reservasi', compact('user', 'kamar', 'tanggalCheckin', 'tanggalCheckout'));
    }

    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $kamar = Kamar::findOrFail($id);

        $request->validate([
            'tanggal_checkin' => 'required|date|after_or_equal:today',
            'jam_checkin' => 'required',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jam_checkout' => 'required',
        ]);

        // Validasi: Cek apakah kamar masih tersedia pada tanggal yang dipilih
        $tanggalCheckin = $request->tanggal_checkin;
        $tanggalCheckout = $request->tanggal_checkout;

        $kamarTerpesan = Reservasi::where('kamar_id', $kamar->id)
            ->whereIn('status', ['pending', 'aktif'])
            ->where(function($query) use ($tanggalCheckin, $tanggalCheckout) {
                // Cek overlap booking
                $query->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout])
                      ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout])
                      ->orWhere(function($q) use ($tanggalCheckin, $tanggalCheckout) {
                          $q->where('tanggal_checkin', '<=', $tanggalCheckin)
                            ->where('tanggal_checkout', '>=', $tanggalCheckout);
                      });
            })
            ->exists();

        if ($kamarTerpesan) {
            return back()->withErrors(['tanggal_checkin' => 'Kamar tidak tersedia pada tanggal yang dipilih. Silakan pilih tanggal lain.'])
                        ->withInput();
        }

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
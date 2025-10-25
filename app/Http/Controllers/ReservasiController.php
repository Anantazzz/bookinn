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
        $tipeKamar = Kamar::findOrFail($id); // ini id yang diklik user (anggap tipe kamar)

        $request->validate([
            'tanggal_checkin' => 'required|date|after_or_equal:today',
            'jam_checkin' => 'required',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'jam_checkout' => 'required',
        ]);

        $tanggalCheckin = $request->tanggal_checkin;
        $tanggalCheckout = $request->tanggal_checkout;

        // Cari kamar ID yang masih tersedia untuk tipe kamar ini
        $kamarTersedia = Kamar::where('tipe_kamar_id', $tipeKamar->tipe_kamar_id)
            ->whereDoesntHave('reservasis', function ($query) use ($tanggalCheckin, $tanggalCheckout) {
                $query->whereIn('status', ['pending', 'aktif'])
                    ->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                        $q->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout])
                            ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout])
                            ->orWhere(function ($r) use ($tanggalCheckin, $tanggalCheckout) {
                                $r->where('tanggal_checkin', '<=', $tanggalCheckin)
                                ->where('tanggal_checkout', '>=', $tanggalCheckout);
                            });
                    });
            })
            ->first(); // ambil satu kamar kosong

        if (!$kamarTersedia) {
            return back()->withErrors(['tanggal_checkin' => 'Semua kamar pada tipe ini sudah penuh di tanggal tersebut.'])
                        ->withInput();
        }

        $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0;
        $hargaKamar = $kamarTersedia->harga;
        $biayaKasur = $kasurTambahan ? 100000 : 0;
        $totalHarga = $hargaKamar + $biayaKasur;

        // Tentukan status otomatis
        if ($tanggalCheckin == date('Y-m-d')) {
            $status = 'aktif'; // langsung aktif kalau checkin hari ini
        } else {
            $status = 'pending'; // kalau belum hari H, pending dulu
        }

        $reservasi = Reservasi::create([
            'user_id' => $user->id,
            'kamar_id' => $kamarTersedia->id, // 🧠 ini penting: kamar kosong yang ditemukan
            'tanggal_checkin' => $tanggalCheckin,
            'jam_checkin' => $request->jam_checkin,
            'tanggal_checkout' => $tanggalCheckout,
            'jam_checkout' => $request->jam_checkout,
            'status' => $status,
            'kasur_tambahan' => $kasurTambahan,
            'total_harga' => $totalHarga,
        ]);
        
        return redirect()->route('hotel.pembayaran', ['id' => $kamarTersedia->id])
                        ->with('success', 'Reservasi berhasil! Silakan lanjutkan ke pembayaran.'); 
    } 
}
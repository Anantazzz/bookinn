<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;

class ReservasiController extends Controller
{
    // TAMPILKAN FORM RESERVASI
    public function showForm(Request $request, $id)
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        $kamar = Kamar::findOrFail($id); // Ambil data kamar berdasarkan ID dari URL

        // Ambil tanggal check-in dan check-out yang dikirim dari halaman detail hotel
        $tanggalCheckin = $request->input('tanggal_checkin');
        $tanggalCheckout = $request->input('tanggal_checkout');

        // Kirim data user, kamar, dan tanggal ke halaman form reservasi
        return view('hotels.reservasi', compact('user', 'kamar', 'tanggalCheckin', 'tanggalCheckout'));
    }

    // SIMPAN DATA RESERVASI KE DATABASE
    public function store(Request $request, $id)
    {
        $user = Auth::user(); // Ambil data user login
        $tipeKamar = Kamar::findOrFail($id); // Ambil data tipe kamar berdasarkan ID yang dipilih user

        // Validasi input dari form reservasi
        $request->validate([
            'tanggal_checkin' => 'required|date|after_or_equal:today', // Tidak boleh sebelum hari ini
            'jam_checkin' => 'required', // Wajib diisi
            'tanggal_checkout' => 'required|date|after:tanggal_checkin', // Harus setelah check-in
            'jam_checkout' => 'required', // Wajib diisi
        ]);

        // Simpan nilai tanggal untuk dipakai di query
        $tanggalCheckin = $request->tanggal_checkin;
        $tanggalCheckout = $request->tanggal_checkout;

        // CARI KAMAR YANG MASIH TERSEDIA
        $kamarTersedia = Kamar::where('tipe_kamar_id', $tipeKamar->tipe_kamar_id) // Cari kamar berdasarkan tipe
            ->whereDoesntHave('reservasis', function ($query) use ($tanggalCheckin, $tanggalCheckout) {
                // Filter kamar yang sedang punya reservasi aktif atau pending
                $query->whereIn('status', ['pending', 'aktif'])
                    ->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                        // Cek apakah tanggal yang diminta bentrok dengan tanggal reservasi lain
                        $q->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout]) // checkin bentrok
                            ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout]) // checkout bentrok
                            ->orWhere(function ($r) use ($tanggalCheckin, $tanggalCheckout) {
                                // Jika tanggal baru berada di dalam rentang tanggal lama
                                $r->where('tanggal_checkin', '<=', $tanggalCheckin)
                                  ->where('tanggal_checkout', '>=', $tanggalCheckout);
                            });
                    });
            })
            ->first(); // Ambil satu kamar kosong saja

        // Jika tidak ada kamar kosong di tanggal itu, tampilkan pesan error
        if (!$kamarTersedia) {
            return back()->withErrors([
                'tanggal_checkin' => 'Semua kamar pada tipe ini sudah penuh di tanggal tersebut.'
            ])->withInput();
        }

        // HITUNG TOTAL HARGA
        $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0; // Apakah user menambah kasur?
        $jumlahHari = \Carbon\Carbon::parse($tanggalCheckin)->diffInDays(\Carbon\Carbon::parse($tanggalCheckout));
        $hargaKamar = $kamarTersedia->harga * $jumlahHari; // Harga kamar dikali jumlah hari
        $biayaKasur = $kasurTambahan ? 100000 : 0; // Tambahan biaya kasur
        $totalHarga = $hargaKamar + $biayaKasur; // Total harga keseluruhan

        // TENTUKAN STATUS RESERVASI OTOMATIS
        if ($tanggalCheckin == date('Y-m-d')) {
            $status = 'aktif'; // Kalau check-in hari ini, langsung aktif
        } else {
            $status = 'pending'; // Kalau belum hari H, status pending dulu
        }

        // SIMPAN DATA RESERVASI KE DATABASE
        $reservasi = Reservasi::create([
            'user_id' => $user->id, // User yang melakukan reservasi
            'kamar_id' => $kamarTersedia->id, // ID kamar yang kosong
            'tanggal_checkin' => $tanggalCheckin, // Tanggal masuk
            'jam_checkin' => $request->jam_checkin, // Jam masuk
            'tanggal_checkout' => $tanggalCheckout, // Tanggal keluar
            'jam_checkout' => $request->jam_checkout, // Jam keluar
            'status' => $status, // Status (aktif/pending)
            'kasur_tambahan' => $kasurTambahan, // Apakah pakai kasur tambahan
            'total_harga' => $totalHarga, // Total biaya yang harus dibayar
        ]);
        
        // UPDATE STATUS KAMAR MENJADI BOOKING
        $kamarTersedia->update(['status' => 'booking']);
        
        // Debug: Log untuk memastikan status kamar terupdate
        \Log::info('Status kamar diupdate', [
            'kamar_id' => $kamarTersedia->id,
            'nomor_kamar' => $kamarTersedia->nomor_kamar,
            'status_baru' => 'booking'
        ]);

        // ARAHKAN KE HALAMAN PEMBAYARAN
        return redirect()->route('hotel.pembayaran', ['id' => $kamarTersedia->id])
                        ->with('success', 'Reservasi berhasil! Silakan lanjutkan ke pembayaran.');
    } 
}

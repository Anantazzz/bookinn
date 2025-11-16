<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\TipeKamar;
use Carbon\Carbon;

class InvoiceOfflineController extends Controller
{
    // Fungsi untuk menampilkan invoice reservasi offline
    public function invoice($id) // Parameter $id adalah ID reservasi
    {
        // Ambil data reservasi lengkap dengan semua relasi terkait
        $reservasi = Reservasi::with(['tamuOffline', 'kamar.tipeKamar', 'pembayaran']) // Include relasi: tamu offline, kamar+tipe kamar, dan pembayaran
            ->findOrFail($id); // Cari reservasi berdasarkan ID atau error 404 jika tidak ditemukan

        // Tampilkan view invoice dengan data reservasi lengkap
        return view('resepsionis.offline.invoice', compact('reservasi')); // Kirim data reservasi ke view invoice offline
    }
}

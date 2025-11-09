<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use App\Models\Pembatalan; // Import model Pembatalan
use App\Models\Reservasi; // Import model Reservasi
use App\Models\Pembayaran; // Import model Pembayaran
use App\Models\Invoice; // Import model Invoice
use Illuminate\Http\Request; // Import Request

class PembatalanController extends Controller // Deklarasi class PembatalanController
{
    public function form($reservasi_id) // Fungsi untuk menampilkan form pembatalan berdasarkan reservasi id
    {
        $reservasi = Reservasi::with(['kamar.hotel', 'tipeKamar', 'pembayaran.invoice'])->findOrFail($reservasi_id); // Ambil reservasi beserta relasi
        $pembayaran = $reservasi->pembayaran; // Ambil pembayaran terkait jika ada
        $invoice = $pembayaran ? $pembayaran->invoice : null; // Ambil invoice jika pembayaran ada

        return view('hotels.pembatalan.form', compact('reservasi', 'pembayaran', 'invoice')); // Render view form pembatalan
    }
  
    //Simpan pembatalan bookingan hotel.
    public function store(Request $request, $reservasi_id) // Fungsi untuk menyimpan pembatalan
    {
        $request->validate([ // Validasi input
            'alasan' => 'required|string|max:255', // Alasan pembatalan wajib
        ]);

        // Cari data reservasi & pembayaran yang terkait
        $reservasi = Reservasi::findOrFail($reservasi_id); // Cari reservasi atau 404
        $pembayaran = Pembayaran::where('reservasi_id', $reservasi->id)->first(); // Ambil pembayaran terkait jika ada

        // Cek apakah reservasi masih bisa dibatalkan
        if ($reservasi->status !== 'pending') { // Jika status bukan pending
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.'); // Kembalikan dengan error
        }

        // Simpan data pembatalan
        $pembatalan = Pembatalan::create([ // Buat record pembatalan
            'reservasi_id' => $reservasi->id, // Referensi reservasi
            'pembayaran_id' => $pembayaran ? $pembayaran->id : null, // Referensi pembayaran jika ada
            'alasan' => $request->alasan, // Alasan pembatalan
            'jumlah_refund' => $pembayaran ? $pembayaran->jumlah_bayar : 0, // Jumlah refund berdasarkan pembayaran
            'status' => 'batal', // Set status pembatalan
        ]);

        // Update status di tabel lain
        $reservasi->update(['status' => 'batal']); // Update status reservasi menjadi batal
        if ($pembayaran) { // Jika ada pembayaran
            $pembayaran->update(['status_bayar' => 'batal']); // Update status pembayaran menjadi batal
        }

        return redirect()->route('pembatalan.show', $pembatalan->id) // Redirect ke halaman struk pembatalan
                         ->with('success', 'Reservasi berhasil dibatalkan.'); // Flash message sukses
    }

    //Tampilkan struk pembatalan.
    public function show($id) // Fungsi untuk menampilkan rincian pembatalan
    {
        $pembatalan = Pembatalan::with([ // Ambil pembatalan beserta relasi untuk tampilan
            'reservasi.kamar.hotel', 
            'reservasi.kamar.tipeKamar', 
            'reservasi.pembayaran.invoice'
        ])->findOrFail($id); // Cari pembatalan atau 404

        $invoice = $pembatalan->reservasi->pembayaran ? $pembatalan->reservasi->pembayaran->invoice : null; // Ambil invoice jika ada

        return view('hotels.pembatalan.show', compact('pembatalan', 'invoice')); // Render view struk pembatalan
    }
} 
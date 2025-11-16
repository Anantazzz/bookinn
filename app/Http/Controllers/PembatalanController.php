<?php
namespace App\Http\Controllers;
use App\Models\Pembatalan;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PembatalanController extends Controller
{
    // ==========================================
    // TAMPILKAN FORM PEMBATALAN RESERVASI
    // ==========================================
    public function form($reservasi_id)
    {
        // AMBIL DATA RESERVASI DENGAN SEMUA RELASI TERKAIT
        $reservasi = Reservasi::with([
            'kamar.hotel', // Relasi: reservasi → kamar → hotel
            'tipeKamar', // Relasi: reservasi → tipe kamar
            'pembayaran.invoice' // Relasi: reservasi → pembayaran → invoice
        ])->findOrFail($reservasi_id); // Cari reservasi berdasarkan ID atau error 404
        
        // AMBIL DATA PEMBAYARAN & INVOICE TERKAIT
        $pembayaran = $reservasi->pembayaran; // Data pembayaran jika sudah ada (null jika belum bayar)
        $invoice = $pembayaran ? $pembayaran->invoice : null; // Data invoice jika pembayaran sudah ada

        // KIRIM DATA KE VIEW FORM PEMBATALAN
        return view('hotels.pembatalan.form', compact('reservasi', 'pembayaran', 'invoice')); // Tampilkan form pembatalan
    }
  
    // ==========================================
    // PROSES PEMBATALAN RESERVASI
    // ==========================================
    public function store(Request $request, $reservasi_id)
    {
        // VALIDASI INPUT FORM PEMBATALAN
        $request->validate([ // Validasi data yang dikirim user
            'alasan' => 'required|string|max:255', // Alasan pembatalan wajib diisi, maksimal 255 karakter
        ]);

        // AMBIL DATA RESERVASI & PEMBAYARAN TERKAIT
        $reservasi = Reservasi::findOrFail($reservasi_id); // Cari reservasi berdasarkan ID atau error 404
        $pembayaran = Pembayaran::where('reservasi_id', $reservasi->id)->first(); // Cari pembayaran terkait (null jika belum bayar)

        // VALIDASI: CEK APAKAH RESERVASI BISA DIBATALKAN
        if ($reservasi->status !== 'pending') { // Hanya reservasi dengan status 'pending' yang bisa dibatalkan
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.'); // Error jika status bukan pending
        }

        // ==========================================
        // SIMPAN DATA PEMBATALAN KE DATABASE
        // ==========================================
        $pembatalan = Pembatalan::create([ // Buat record pembatalan baru
            'reservasi_id' => $reservasi->id, // ID reservasi yang dibatalkan
            'pembayaran_id' => $pembayaran ? $pembayaran->id : null, // ID pembayaran (null jika belum bayar)
            'alasan' => $request->alasan, // Alasan pembatalan dari user
            'jumlah_refund' => $pembayaran ? $pembayaran->jumlah_bayar : 0, // Jumlah refund = jumlah yang sudah dibayar
            'status' => 'batal', // Status pembatalan
        ]);

        // ==========================================
        // UPDATE STATUS DI TABEL TERKAIT
        // ==========================================
        $reservasi->update(['status' => 'batal']); // Ubah status reservasi menjadi 'batal'
        if ($pembayaran) { // Jika sudah ada pembayaran
            $pembayaran->update(['status_bayar' => 'gagal']); // Ubah status pembayaran menjadi 'gagal'
        }

        // REDIRECT KE HALAMAN STRUK PEMBATALAN
        return redirect()->route('pembatalan.show', $pembatalan->id) // Ke halaman detail pembatalan
                         ->with('success', 'Reservasi berhasil dibatalkan.'); // Flash message sukses
    }

    // ==========================================
    // TAMPILKAN STRUK/DETAIL PEMBATALAN
    // ==========================================
    public function show($id)
    {
        // AMBIL DATA PEMBATALAN DENGAN SEMUA RELASI TERKAIT
        $pembatalan = Pembatalan::with([ // Ambil pembatalan beserta relasi lengkap
            'reservasi.kamar.hotel', // Relasi: pembatalan → reservasi → kamar → hotel
            'reservasi.kamar.tipeKamar', // Relasi: pembatalan → reservasi → kamar → tipe kamar
            'reservasi.pembayaran.invoice' // Relasi: pembatalan → reservasi → pembayaran → invoice
        ])->findOrFail($id); // Cari pembatalan berdasarkan ID atau error 404

        // AMBIL DATA INVOICE JIKA ADA
        $invoice = $pembatalan->reservasi->pembayaran ? // Jika ada pembayaran
                   $pembatalan->reservasi->pembayaran->invoice : // Ambil invoice-nya
                   null; // Jika tidak ada pembayaran, invoice = null

        // KIRIM DATA KE VIEW STRUK PEMBATALAN
        return view('hotels.pembatalan.show', compact('pembatalan', 'invoice')); // Tampilkan struk pembatalan
    }
} 
<?php
namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // ==========================================
    // TAMPILKAN INVOICE DI BROWSER 
    // ==========================================
    public function show($id)
    {
        // AMBIL DATA INVOICE DENGAN SEMUA RELASI TERKAIT
        $invoice = Invoice::with([
            'pembayaran.reservasi.kamar.hotel', // Relasi: invoice → pembayaran → reservasi → kamar → hotel
            'pembayaran.reservasi.kamar.tipeKamar' // Relasi: invoice → pembayaran → reservasi → kamar → tipe kamar
        ])->findOrFail($id); // Cari invoice berdasarkan ID atau error 404

        // AMBIL DATA RESERVASI DARI RELASI
        $reservasi = $invoice->pembayaran->reservasi ?? null; // Ambil reservasi terkait (null jika tidak ada)

        // VALIDASI: PASTIKAN DATA RESERVASI ADA
        if (!$reservasi) { // Jika reservasi tidak ditemukan
            abort(404, 'Data reservasi tidak ditemukan untuk invoice ini.'); // Error 404 dengan pesan khusus
        }

        // KIRIM DATA KE VIEW INVOICE HTML
        return view('hotels.invoice', [ // Tampilkan invoice dalam format HTML
            'reservasi' => $reservasi, // Data lengkap reservasi (kamar, hotel, user, dll)
            'kode_unik' => $invoice->kode_unik, // Kode unik invoice (misal: INV-ABC1234)
        ]);
    }
   
    // ==========================================
    // DOWNLOAD INVOICE SEBAGAI FILE PDF
    // ==========================================
    public function download($id)
    {
        // AMBIL DATA RESERVASI DENGAN RELASI TERKAIT
        $reservasi = Reservasi::with([
            'kamar.tipeKamar', // Relasi: reservasi → kamar → tipe kamar
            'pembayaran' // Relasi: reservasi → pembayaran
        ])->findOrFail($id); // Cari reservasi berdasarkan ID atau error 404

        // CEK APAKAH INVOICE SUDAH ADA UNTUK PEMBAYARAN INI
        $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id ?? null) // Cari invoice berdasarkan pembayaran ID
                          ->first(); // Ambil yang pertama atau null

        // BUAT INVOICE BARU JIKA BELUM ADA
        if (!$invoice) { // Jika invoice belum pernah dibuat
            $invoice = Invoice::create([ // Buat record invoice baru
                'pembayaran_id' => $reservasi->pembayaran->id, // ID pembayaran terkait
                'kode_unik' => 'INV-' . strtoupper(Str::random(7)), // Generate kode unik: INV-ABC1234
            ]);
        }

        // GENERATE PDF DARI VIEW BLADE
        $pdf = Pdf::loadView('hotels.invoice', [ // Buat PDF dari template HTML
            'reservasi' => $reservasi, // Data reservasi lengkap
            'kode_unik' => $invoice->kode_unik, // Kode unik invoice
        ]);

        // DOWNLOAD FILE PDF KE BROWSER USER
        return $pdf->download('invoice_' . $reservasi->id . '.pdf'); // Nama file: invoice_123.pdf
    }
} 
<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use App\Models\Invoice; // Import model Invoice
use App\Models\Reservasi; // Import model Reservasi
use Barryvdh\DomPDF\Facade\Pdf; // Import facade PDF
use Illuminate\Support\Str; // Import helper Str
use Illuminate\Http\Request; // Import Request

class InvoiceController extends Controller // Deklarasi class InvoiceController
{
    public function show($id) // Fungsi untuk menampilkan invoice di browser
    {
        $invoice = Invoice::with(['pembayaran.reservasi.kamar.tipeKamar'])->findOrFail($id); // Ambil invoice beserta relasi

        $reservasi = $invoice->pembayaran->reservasi ?? null; // Ambil reservasi terkait jika ada

        if (!$reservasi) { // Jika tidak ada reservasi
            abort(404, 'Data reservasi tidak ditemukan untuk invoice ini.'); // Kembalikan 404
        }

        return view('hotels.invoice', [ // Render view invoice dengan data
            'reservasi' => $reservasi, // Data reservasi
            'kode_unik' => $invoice->kode_unik, // Kode unik invoice
        ]);
    }
   
    public function download($id) // Fungsi untuk mengunduh invoice sebagai PDF
    {
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id); // Ambil reservasi beserta relasi

        $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id ?? null)->first(); // Cek apakah invoice sudah ada untuk pembayaran ini

        if (!$invoice) { // Jika belum ada, buat invoice baru
            $invoice = Invoice::create([
                'pembayaran_id' => $reservasi->pembayaran->id, // Referensi pembayaran
                'kode_unik' => 'INV-' . strtoupper(Str::random(7)), // Buat kode unik baru
            ]);
        }

        $pdf = Pdf::loadView('hotels.invoice', [ // Generate PDF dari view
            'reservasi' => $reservasi, // Data reservasi
            'kode_unik' => $invoice->kode_unik, // Kode unik invoice
        ]);

        return $pdf->download('invoice_' . $reservasi->id . '.pdf'); // Unduh file PDF
    }
} 
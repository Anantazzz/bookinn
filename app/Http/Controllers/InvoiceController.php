<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Tampilkan halaman invoice
    public function show($id)
    {
        // Ambil invoice berdasarkan ID invoice
        $invoice = Invoice::with(['pembayaran.reservasi.kamar.tipeKamar'])->findOrFail($id);

        // Ambil data reservasi lewat pembayaran
        $reservasi = $invoice->pembayaran->reservasi ?? null;

        if (!$reservasi) {
            abort(404, 'Data reservasi tidak ditemukan untuk invoice ini.');
        }

        return view('hotels.invoice', [
            'reservasi' => $reservasi,
            'kode_unik' => $invoice->kode_unik,
        ]);
    }
    
    // Download invoice sebagai PDF
    public function download($id)
    {
        // Ambil data reservasi beserta pembayaran
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);

        // Ambil invoice berdasarkan pembayaran
        $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id ?? null)->first();

        // Kalau belum ada invoice, buat baru
        if (!$invoice) {
            $invoice = Invoice::create([
                'pembayaran_id' => $reservasi->pembayaran->id,
                'kode_unik' => 'INV-' . strtoupper(Str::random(7)),
            ]);
        }

        // Buat PDF
        $pdf = Pdf::loadView('hotels.invoice', [
            'reservasi' => $reservasi,
            'kode_unik' => $invoice->kode_unik,
        ]);

        return $pdf->download('invoice_' . $reservasi->id . '.pdf');
    }
}

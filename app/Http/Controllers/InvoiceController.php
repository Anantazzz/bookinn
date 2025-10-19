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
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);

        // Cek apakah invoice sudah ada untuk reservasi ini
        $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id ?? 0)->first();


        if (!$invoice) {
            // kalau belum ada → generate dan simpan
            $kode_unik = 'INV-' . strtoupper(Str::random(7));

            $invoice = Invoice::create([
                'reservasi_id' => $reservasi->id,
                'kode_unik' => $kode_unik,
            ]);
        }

        return view('hotels.invoice', [
            'reservasi' => $reservasi,
            'kode_unik' => $invoice->kode_unik,
        ]);
    }

    // Download invoice sebagai PDF
    public function download($id)
    {
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);
        $invoice = Invoice::where('reservasi_id', $reservasi->id)->first();

        if (!$invoice) {
            // fallback kalau belum ada
            $invoice = Invoice::create([
                'reservasi_id' => $reservasi->id,
                'kode_unik' => 'INV-' . strtoupper(Str::random(7)),
            ]);
        }

        $pdf = Pdf::loadView('hotels.invoice', [
            'reservasi' => $reservasi,
            'kode_unik' => $invoice->kode_unik,
        ]);

        return $pdf->download('invoice_' . $reservasi->id . '.pdf');
    }
}

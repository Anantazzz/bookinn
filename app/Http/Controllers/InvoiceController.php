<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $invoice = Invoice::with(['pembayaran.reservasi.kamar.tipeKamar'])->findOrFail($id);

        $reservasi = $invoice->pembayaran->reservasi ?? null;

        if (!$reservasi) {
            abort(404, 'Data reservasi tidak ditemukan untuk invoice ini.');
        }

        return view('hotels.invoice', [
            'reservasi' => $reservasi,
            'kode_unik' => $invoice->kode_unik,
        ]);
    }
   
    public function download($id)
    {
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);

        $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id ?? null)->first();

        if (!$invoice) {
            $invoice = Invoice::create([
                'pembayaran_id' => $reservasi->pembayaran->id,
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

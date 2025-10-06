<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Tampilkan halaman invoice
    public function show($id)
    {
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);

        return view('hotels.invoice', compact('reservasi'));
    }

    // Download invoice sebagai PDF
    public function download($id)
    {
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);

        $pdf = Pdf::loadView('hotels.invoice', compact('reservasi'));
        return $pdf->download('invoice_' . $reservasi->id . '.pdf');
    }
}

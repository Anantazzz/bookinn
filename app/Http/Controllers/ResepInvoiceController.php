<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ResepInvoiceController extends Controller
{
    // Tampilkan daftar pembayaran (yang pending & success)
    public function index()
    {
        $pembayarans = Pembayaran::with(['reservasi.user', 'reservasi.kamar.tipeKamar'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resepsionis.invoice.index', compact('pembayarans'));
    }

    // Terima pembayaran & buat invoice
    public function accept($id)
    {
        $pembayaran = Pembayaran::with('reservasi.kamar')->findOrFail($id);

        if ($pembayaran->status_bayar === 'lunas') {
            return back()->with('error', 'Pembayaran sudah diterima sebelumnya.');
        }

        // Generate invoice PDF
        $reservasi = $pembayaran->reservasi;
        $malam = Carbon::parse($reservasi->tanggal_checkin)
            ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        $hargaPerMalam = $reservasi->kamar->harga;
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;
        $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;
  
        // Ubah status pembayaran jadi success
        $pembayaran->update([
            'status_bayar' => 'lunas',
            'harga' => $totalBayar, // update harga totalnya juga
        ]);

        $pdf = Pdf::loadView('hotels.invoice', compact('reservasi'));
        $filename = 'invoice_' . $pembayaran->id . '_' . time() . '.pdf';
        Storage::put('public/invoices/' . $filename, $pdf->output());

        // Simpan ke tabel invoices
        Invoice::create([
            'pembayaran_id' => $pembayaran->id,
            'tanggal_cetak' => now(),
            'total' => $totalBayar,
            'file_invoice' => 'invoices/' . $filename,
        ]);

        return back()->with('lunas', 'Pembayaran berhasil diterima & invoice dibuat.');
    }

    // Cetak ulang invoice
    public function print($id)
    {
        $invoice = Invoice::with('pembayaran.reservasi.kamar')->findOrFail($id);
        $reservasi = $invoice->pembayaran->reservasi;

        $pdf = Pdf::loadView('hotels.invoice', compact('reservasi'));
        return $pdf->download('invoice_' . $invoice->id . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pembatalan;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PembatalanController extends Controller
{
    public function form($reservasi_id)
    {
        $reservasi = Reservasi::with(['kamar.hotel', 'tipe_kamar', 'pembayaran.invoice'])->findOrFail($reservasi_id);
        $pembayaran = $reservasi->pembayaran;
        $invoice = $pembayaran ? $pembayaran->invoice : null;

        return view('hotels.pembatalan.form', compact('reservasi', 'pembayaran', 'invoice'));
    }
  
    //Simpan pembatalan bookingan hotel.
    public function store(Request $request, $reservasi_id)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        // Cari data reservasi & pembayaran yang terkait
        $reservasi = Reservasi::findOrFail($reservasi_id);
        $pembayaran = Pembayaran::where('reservasi_id', $reservasi->id)->first();

        // Cek apakah reservasi masih bisa dibatalkan
        if ($reservasi->status !== 'pending') {
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.');
        }

        // Simpan data pembatalan
        $pembatalan = Pembatalan::create([
            'reservasi_id' => $reservasi->id,
            'pembayaran_id' => $pembayaran ? $pembayaran->id : null,
            'alasan' => $request->alasan,
            'jumlah_refund' => $pembayaran ? $pembayaran->jumlah_bayar : 0,
            'status' => 'batal',
        ]);

        // Update status di tabel lain
        $reservasi->update(['status' => 'batal']);
        if ($pembayaran) {
            $pembayaran->update(['status_bayar' => 'batal']);
        }

        return redirect()->route('pembatalan.show', $pembatalan->id)
                         ->with('success', 'Reservasi berhasil dibatalkan.');
    }

    //Tampilkan struk pembatalan.
    public function show($id)
    {
        $pembatalan = Pembatalan::with([
            'reservasi.kamar.hotel', 
            'reservasi.kamar.tipeKamar', 
            'reservasi.pembayaran.invoice'
        ])->findOrFail($id);

        $invoice = $pembatalan->reservasi->pembayaran ? $pembatalan->reservasi->pembayaran->invoice : null;

        return view('hotels.pembatalan.show', compact('pembatalan', 'invoice'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    // tampilkan form pembayaran
   public function show($id)
   {
    $user = Auth::user(); // data user login
    $kamar = Kamar::findOrFail($id); // data kamar sesuai id
    $kode_unik = 'INV-' . strtoupper(Str::random(7));

    // Ambil reservasi terbaru milik user untuk kamar ini
    $reservasi = Reservasi::where('user_id', $user->id)
                          ->where('kamar_id', $kamar->id)
                          ->latest()
                          ->first();

    if ($reservasi) {
        // Hitung jumlah malam menginap
        $malam = Carbon::parse($reservasi->tanggal_checkin)
                    ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        // Hitung total harga
        $hargaPerMalam = $kamar->harga;
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;
        $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;

        // Simpan ke properti model biar bisa ditampilkan di Blade
        $reservasi->total_harga = $totalBayar;
    }

    return view('hotels.pembayaran', compact('user', 'kamar', 'reservasi'));
    }

    // proses pembayaran (form kedua)
    public function prosesPembayaran(Request $request, $id)
    {
        $validated = $request->validate([
            'bank' => 'required|in:mandiri,bca,bri,bni,btn',
            'atas_nama' => 'nullable|string|max:255',
            'nomor_rekening' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $kamar = Kamar::findOrFail($id);

        // Ambil reservasi terbaru dari user ini untuk kamar tsb
        $reservasi = Reservasi::where('user_id', $user->id)
                            ->where('kamar_id', $kamar->id)
                            ->latest()
                            ->firstOrFail();

        // ✅ Hitung ulang total harga berdasarkan lama menginap & kasur tambahan
        $malam = Carbon::parse($reservasi->tanggal_checkin)
                    ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        $hargaPerMalam = $reservasi->kamar->harga;
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;
        $jumlahBayar = ($hargaPerMalam * $malam) + $hargaKasur;

        // ✅ Simpan data pembayaran (termasuk bank & no rekening)
        $pembayaran = Pembayaran::create([
            'reservasi_id' => $reservasi->id,
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => $jumlahBayar,
            'metode_bayar' => 'transfer',
            'bank' => $validated['bank'],
            'atas_nama' => $validated['atas_nama'] ?? null,
            'nomor_rekening' => $validated['nomor_rekening'],
            'status_bayar' => 'pending',
        ]);

        // --- Generate invoice ---
        $pdf = Pdf::loadView('hotels.invoice', compact('reservasi'));
        $filename = 'invoice_' . $reservasi->id . '_' . time() . '.pdf';
        Storage::put('public/invoices/' . $filename, $pdf->output());
 
        // Generate kode unik otomatis
        $kode_unik = 'INV-' . strtoupper(Str::random(7));

        // Simpan record invoice
        Invoice::create([
            'pembayaran_id' => $pembayaran->id,
            'tanggal_cetak' => now(),
            'total' => $jumlahBayar,
            'file_invoice' => 'invoices/' . $filename,
            'kode_unik' => $kode_unik, // ✅ wajib diisi
        ]);

        return redirect()->route('invoice.show', ['id' => $reservasi->id])
                        ->with('success', 'Pembayaran berhasil & invoice dibuat!');
    }
}
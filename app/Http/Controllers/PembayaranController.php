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
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    // tampilkan form pembayaran
   public function show($id)
   {
    $user = Auth::user(); // data user login
    $kamar = Kamar::findOrFail($id); // data kamar sesuai id

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
        'bank' => 'required',
        'nama_pengirim' => 'nullable|string|max:255',
    ]);

    $user = Auth::user();
    $kamar = Kamar::findOrFail($id);

    // Ambil reservasi terbaru dari user ini untuk kamar tsb
    $reservasi = Reservasi::where('user_id', $user->id)
                          ->where('kamar_id', $kamar->id)
                          ->latest()
                          ->firstOrFail();

    // Ambil total harga dari reservasi (termasuk kasur tambahan)
    $jumlahBayar = $reservasi->total_harga;

    // Simpan data pembayaran
    $pembayaran = Pembayaran::create([
        'reservasi_id' => $reservasi->id,
        'tanggal_bayar' => now()->toDateString(),
        'jumlah_bayar' => $jumlahBayar,
        'metode_bayar' => 'transfer',
        'status_bayar' => 'pending',
    ]);

    // ✅ PERBAIKAN: Status reservasi tetap 'pending' setelah bayar
    // Status baru berubah jadi 'aktif' saat resepsionis klik Check-in (di tanggal check-in)
    // JANGAN update status di sini!
    // $reservasi->update(['status' => 'aktif']); // <- DIHAPUS!

    // --- Generate invoice ---
    $malam = Carbon::parse($reservasi->tanggal_checkin)
                ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));
    $hargaPerMalam = $reservasi->kamar->harga;
    $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;
    $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;

    // Generate PDF
    $pdf = Pdf::loadView('hotels.invoice', compact('reservasi'));
    $filename = 'invoice_' . $reservasi->id . '_' . time() . '.pdf';
    Storage::put('public/invoices/' . $filename, $pdf->output());

    // Simpan record invoice
    Invoice::create([
        'pembayaran_id' => $pembayaran->id,
        'tanggal_cetak' => now(),
        'total' => $totalBayar,
        'file_invoice' => 'invoices/' . $filename,
    ]);

    return redirect()->route('invoice.show', ['id' => $reservasi->id])
                    ->with('success', 'Pembayaran berhasil & invoice dibuat!');
        }
    }
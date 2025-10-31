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
    public function show($id)
    {
        $user = Auth::user(); 
        $kamar = Kamar::findOrFail($id);
        $kode_unik = 'INV-' . strtoupper(Str::random(7)); 

        $reservasi = Reservasi::where('user_id', $user->id)
                              ->where('kamar_id', $kamar->id)
                              ->latest() 
                              ->first(); 

        if ($reservasi) {
    
            $malam = Carbon::parse($reservasi->tanggal_checkin)
                        ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

            $hargaPerMalam = $kamar->harga;

            $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;

            $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;

            $reservasi->total_harga = $totalBayar;
        }

        return view('hotels.pembayaran', compact('user', 'kamar', 'reservasi'));
    }

    public function prosesPembayaran(Request $request, $id)
    {
        try {
            logger()->info('Data yang diterima:', [
                'request_all' => $request->all(),
                'files' => $request->allFiles(),
                'id' => $id
            ]);

            $validated = $request->validate([
                'bank' => 'required|in:mandiri,bca,bri,bni,btn',
                'atas_nama' => 'nullable|string|max:255',
                'nomor_rekening' => 'required|string|max:20',
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

        $user = Auth::user();
        $kamar = Kamar::findOrFail($id); 

        $reservasi = Reservasi::where('user_id', $user->id)
                              ->where('kamar_id', $kamar->id)
                              ->latest()
                              ->firstOrFail();

        $malam = Carbon::parse($reservasi->tanggal_checkin)
                    ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        $hargaPerMalam = $reservasi->kamar->harga;

        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;

        $jumlahBayar = ($hargaPerMalam * $malam) + $hargaKasur;

        $buktiTransfer = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
   
            $fileName = 'bukti_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();

            $uploadPath = public_path('images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($file->move($uploadPath, $fileName)) {
                $buktiTransfer = 'images/' . $fileName;
                logger()->info('Bukti transfer berhasil diupload', ['path' => $buktiTransfer]);
            } else {
                throw new \Exception('Gagal mengupload bukti transfer');
            }
        } else {
            throw new \Exception('File bukti transfer tidak ditemukan');
        }

        $pembayaran = Pembayaran::create([
            'reservasi_id' => $reservasi->id,
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => $jumlahBayar,
            'metode_bayar' => 'transfer',
            'bank' => $validated['bank'],
            'atas_nama' => $validated['atas_nama'] ?? null,
            'bukti_transfer' => $buktiTransfer,
            'nomor_rekening' => $validated['nomor_rekening'],
            'status_bayar' => 'pending',
        ]);

        try {
            $invoiceData = [
                'reservasi' => $reservasi,
                'pembayaran' => $pembayaran,
                'hotel' => $kamar->hotel,
                'user' => Auth::user()
            ];

            $pdf = Pdf::loadView('hotels.invoice', $invoiceData);
            
            $invoiceDir = public_path('invoices');
            if (!file_exists($invoiceDir)) {
                mkdir($invoiceDir, 0755, true);
            }

            $filename = 'invoice_' . $reservasi->id . '_' . time() . '_' . Str::random(5) . '.pdf';
            $pdfPath = public_path('invoices/' . $filename);

            logger()->info('Mencoba menyimpan PDF:', [
                'path' => $pdfPath
            ]);

            $pdf->save($pdfPath);

            logger()->info('PDF berhasil dibuat', [
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            logger()->error('Error saat membuat PDF:', [
                'message' => $e->getMessage()
            ]);
            throw new \Exception('Gagal membuat invoice PDF: ' . $e->getMessage());
        }

        $kode_unik = 'INV-' . strtoupper(Str::random(7));

        $invoice = Invoice::create([
            'pembayaran_id' => $pembayaran->id, 
            'tanggal_cetak' => now(), 
            'total' => $jumlahBayar,
            'file_invoice' => 'invoices/' . $filename,
            'kode_unik' => $kode_unik, 
        ]);

        return redirect()->route('invoice.show', ['id' => $invoice->id])
                        ->with('success', 'Pembayaran berhasil & invoice dibuat!');

        } catch (\Exception $e) {
            logger()->error('Error dalam proses pembayaran:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $imageDir = public_path('images');
            $invoiceDir = public_path('invoices');
            
            logger()->info('Directory Status:', [
                'images_exists' => file_exists($imageDir),
                'images_writable' => is_writable($imageDir),
                'invoices_exists' => file_exists($invoiceDir),
                'invoices_writable' => is_writable($invoiceDir)
            ]);
            
            return back()
                ->withErrors(['error' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }
    }
}

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
    // =============================
    // 🔹 MENAMPILKAN FORM PEMBAYARAN
    // =============================
    public function show($id)
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        $kamar = Kamar::findOrFail($id); // Ambil data kamar berdasarkan ID
        $kode_unik = 'INV-' . strtoupper(Str::random(7)); // Buat kode unik invoice (contoh: INV-AB12XYZ)

        // Ambil reservasi terbaru milik user untuk kamar ini
        $reservasi = Reservasi::where('user_id', $user->id)
                              ->where('kamar_id', $kamar->id)
                              ->latest() // Urutkan dari yang terbaru
                              ->first(); // Ambil satu data terakhir

        // Jika ada reservasi
        if ($reservasi) {
            // Hitung jumlah malam menginap berdasarkan tanggal check-in & check-out
            $malam = Carbon::parse($reservasi->tanggal_checkin)
                        ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

            // Hitung harga per malam
            $hargaPerMalam = $kamar->harga;

            // Tambahan biaya kasur jika user memilih kasur tambahan
            $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;

            // Hitung total bayar (harga kamar x malam + kasur tambahan)
            $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;

            // Simpan total harga ke dalam objek reservasi agar bisa ditampilkan di Blade
            $reservasi->total_harga = $totalBayar;
        }

        // Kirim data ke view 'hotels.pembayaran'
        return view('hotels.pembayaran', compact('user', 'kamar', 'reservasi'));
    }

    // ====================================
    // 🔹 PROSES PEMBAYARAN (SETELAH FORM)
    // ====================================
    public function prosesPembayaran(Request $request, $id)
    {
        try {
            // Debug: Log semua data yang diterima
            logger()->info('Data yang diterima:', [
                'request_all' => $request->all(),
                'files' => $request->allFiles(),
                'id' => $id
            ]);

            // Validasi input dari form
            $validated = $request->validate([
                'bank' => 'required|in:mandiri,bca,bri,bni,btn',
                'atas_nama' => 'nullable|string|max:255',
                'nomor_rekening' => 'required|string|max:20',
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

        $user = Auth::user(); // Ambil data user login
        $kamar = Kamar::findOrFail($id); // Ambil data kamar sesuai ID

        // Ambil reservasi terbaru user ini untuk kamar tersebut
        $reservasi = Reservasi::where('user_id', $user->id)
                              ->where('kamar_id', $kamar->id)
                              ->latest()
                              ->firstOrFail();

        // Hitung ulang jumlah malam menginap
        $malam = Carbon::parse($reservasi->tanggal_checkin)
                    ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        // Ambil harga kamar dari relasi
        $hargaPerMalam = $reservasi->kamar->harga;

        // Cek apakah user menggunakan kasur tambahan
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;

        // Hitung total pembayaran
        $jumlahBayar = ($hargaPerMalam * $malam) + $hargaKasur;

        // Handle file upload
        $buktiTransfer = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            
            // Generate nama file yang unik
            $fileName = 'bukti_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            
            // Pastikan direktori ada
            $uploadPath = public_path('images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Upload file
            if ($file->move($uploadPath, $fileName)) {
                $buktiTransfer = 'images/' . $fileName;
                logger()->info('Bukti transfer berhasil diupload', ['path' => $buktiTransfer]);
            } else {
                throw new \Exception('Gagal mengupload bukti transfer');
            }
        } else {
            throw new \Exception('File bukti transfer tidak ditemukan');
        }

        // ==================================
        // 🔸 Simpan data pembayaran ke tabel
        // ==================================
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

        // ==================================
        // 🔸 Generate file PDF invoice otomatis
        // ==================================
        try {
            // Generate Invoice PDF
            $invoiceData = [
                'reservasi' => $reservasi,
                'pembayaran' => $pembayaran,
                'hotel' => $kamar->hotel,
                'user' => Auth::user()
            ];

            $pdf = Pdf::loadView('hotels.invoice', $invoiceData);
            
            // Ensure invoices directory exists
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

        // Buat kode invoice unik
        $kode_unik = 'INV-' . strtoupper(Str::random(7));

        // ==================================
        // 🔸 Simpan data invoice ke database
        // ==================================
        $invoice = Invoice::create([
            'pembayaran_id' => $pembayaran->id, // relasi ke pembayaran
            'tanggal_cetak' => now(), // waktu cetak invoice
            'total' => $jumlahBayar, // total bayar
            'file_invoice' => 'invoices/' . $filename, // path file PDF disimpan
            'kode_unik' => $kode_unik, // kode invoice unik
        ]);

        // ==================================
        // 🔸 Arahkan ke halaman invoice
        // ==================================
        return redirect()->route('invoice.show', ['id' => $invoice->id])
                        ->with('success', 'Pembayaran berhasil & invoice dibuat!');

        } catch (\Exception $e) {
            // Log error detail
            logger()->error('Error dalam proses pembayaran:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Debug folder permissions
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

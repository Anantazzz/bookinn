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
    public function show($id) // Fungsi untuk menampilkan halaman pembayaran
    {
        $user = Auth::user(); // Ambil user yang sedang login
        $kamar = Kamar::findOrFail($id); // Cari kamar berdasarkan id atau 404
        $kode_unik = 'INV-' . strtoupper(Str::random(7)); // Buat kode unik sementara

        $reservasi = Reservasi::where('user_id', $user->id) // Cari reservasi user untuk kamar ini
                              ->where('kamar_id', $kamar->id)
                              ->latest() // Ambil reservasi terbaru
                              ->first(); // Ambil hasil pertama

        if ($reservasi) { // Jika ada reservasi
    
            $malam = Carbon::parse($reservasi->tanggal_checkin) // Hitung jumlah malam
                        ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

            $hargaPerMalam = $kamar->harga; // Harga per malam dari kamar

            $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0; // Tambahan kasur jika ada

            $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur; // Total yang harus dibayar

            $reservasi->total_harga = $totalBayar; // Set total harga di object reservasi
        }

        return view('hotels.pembayaran', compact('user', 'kamar', 'reservasi')); // Render view pembayaran
    }

    public function prosesPembayaran(Request $request, $id) // Fungsi untuk memproses pembayaran
    {
        try {
            logger()->info('Data yang diterima:', [ // Log data request untuk debugging
                'request_all' => $request->all(),
                'files' => $request->allFiles(),
                'id' => $id
            ]);

            $validated = $request->validate([ // Validasi input
                'bank' => 'required|in:mandiri,bca,bri,bni,btn', // Pilihan bank
                'atas_nama' => 'nullable|string|max:255', // Nama pemilik rekening
                'nomor_rekening' => 'required|string|max:20', // Nomor rekening
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Bukti transfer
            ]);

        $user = Auth::user(); // Ambil user
        $kamar = Kamar::findOrFail($id); // Ambil kamar

        $reservasi = Reservasi::where('user_id', $user->id) // Ambil reservasi terkait
                              ->where('kamar_id', $kamar->id)
                              ->latest()
                              ->firstOrFail(); // Ambil atau fail

        $malam = Carbon::parse($reservasi->tanggal_checkin) // Hitung lama menginap
                    ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        $hargaPerMalam = $reservasi->kamar->harga; // Harga kamar dari reservasi

        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0; // Biaya kasur tambahan

        $jumlahBayar = ($hargaPerMalam * $malam) + $hargaKasur; // Hitung jumlah bayar

        $buktiTransfer = null; // Inisialisasi path bukti transfer
        if ($request->hasFile('bukti_transfer')) { // Jika ada file bukti transfer
            $file = $request->file('bukti_transfer'); // Ambil file
   
            $fileName = 'bukti_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension(); // Buat nama file unik

            $uploadPath = public_path('images'); // Path upload
            if (!file_exists($uploadPath)) { // Jika folder tidak ada
                mkdir($uploadPath, 0755, true); // Buat folder
            }
            
            if ($file->move($uploadPath, $fileName)) { // Pindahkan file
                $buktiTransfer = 'images/' . $fileName; // Simpan path relatif
                logger()->info('Bukti transfer berhasil diupload', ['path' => $buktiTransfer]); // Log sukses
            } else {
                throw new \Exception('Gagal mengupload bukti transfer'); // Throw jika gagal
            }
        } else {
            throw new \Exception('File bukti transfer tidak ditemukan'); // Throw jika file tidak ditemukan
        }

        $pembayaran = Pembayaran::create([ // Simpan data pembayaran
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
            $invoiceData = [ // Siapkan data untuk invoice PDF
                'reservasi' => $reservasi,
                'pembayaran' => $pembayaran,
                'hotel' => $kamar->hotel,
                'user' => Auth::user()
            ];

            $pdf = Pdf::loadView('hotels.invoice', $invoiceData); // Generate PDF dari view
            
            $invoiceDir = public_path('invoices'); // Path folder invoices
            if (!file_exists($invoiceDir)) { // Jika folder invoices tidak ada
                mkdir($invoiceDir, 0755, true); // Buat folder invoices
            }

            $filename = 'invoice_' . $reservasi->id . '_' . time() . '_' . Str::random(5) . '.pdf'; // Nama file invoice
            $pdfPath = public_path('invoices/' . $filename); // Path lengkap file

            logger()->info('Mencoba menyimpan PDF:', [ // Log sebelum menyimpan
                'path' => $pdfPath
            ]);

            $pdf->save($pdfPath); // Simpan PDF ke disk

            logger()->info('PDF berhasil dibuat', [ // Log sukses pembuatan PDF
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            logger()->error('Error saat membuat PDF:', [ // Log error pembuatan PDF
                'message' => $e->getMessage()
            ]);
            throw new \Exception('Gagal membuat invoice PDF: ' . $e->getMessage()); // Throw exception baru
        }

        $kode_unik = 'INV-' . strtoupper(Str::random(7)); // Buat kode unik untuk invoice

        $invoice = Invoice::create([ // Simpan data invoice ke database
            'pembayaran_id' => $pembayaran->id, 
            'tanggal_cetak' => now(), 
            'total' => $jumlahBayar,
            'file_invoice' => 'invoices/' . $filename,
            'kode_unik' => $kode_unik, 
        ]);

        return redirect()->route('invoice.show', ['id' => $invoice->id]) // Redirect ke halaman invoice
                        ->with('success', 'Pembayaran berhasil & invoice dibuat!'); // Flash pesan sukses

        } catch (\Exception $e) {
            logger()->error('Error dalam proses pembayaran:', [ // Log error umum proses pembayaran
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $imageDir = public_path('images'); // Path images
            $invoiceDir = public_path('invoices'); // Path invoices
            
            logger()->info('Directory Status:', [ // Log status direktori
                'images_exists' => file_exists($imageDir),
                'images_writable' => is_writable($imageDir),
                'invoices_exists' => file_exists($invoiceDir),
                'invoices_writable' => is_writable($invoiceDir)
            ]);
            
            return back() // Kembali ke halaman sebelumnya dengan error
                ->withErrors(['error' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }
    }
} 
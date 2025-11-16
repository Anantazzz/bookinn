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
    // ==========================================
    // TAMPILKAN HALAMAN FORM PEMBAYARAN
    // ==========================================
    public function show($id)
    {
        // AMBIL DATA USER & KAMAR
        $user = Auth::user(); // Data user yang akan melakukan pembayaran
        $kamar = Kamar::findOrFail($id); // Data kamar yang dipesan (error 404 jika tidak ada)
        $kode_unik = 'INV-' . strtoupper(Str::random(7)); // Generate kode invoice sementara untuk preview

        // CARI RESERVASI TERKAIT USER & KAMAR INI
        $reservasi = Reservasi::where('user_id', $user->id) // Reservasi milik user ini
                              ->where('kamar_id', $kamar->id) // Untuk kamar ini
                              ->latest() // Ambil yang paling baru (jika ada beberapa)
                              ->first(); // Ambil satu record atau null

        // HITUNG ULANG TOTAL HARGA (UNTUK MEMASTIKAN AKURASI)
        if ($reservasi) { // Jika reservasi ditemukan
    
            $malam = Carbon::parse($reservasi->tanggal_checkin) // Hitung durasi menginap
                        ->diffInDays(Carbon::parse($reservasi->tanggal_checkout)); // Selisih hari = jumlah malam

            $hargaPerMalam = $kamar->harga; // Harga kamar per malam dari tabel kamars

            $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0; // Biaya kasur tambahan jika dipilih

            $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur; // Total = (harga × malam) + kasur tambahan

            $reservasi->total_harga = $totalBayar; // Update total harga di object (tidak disimpan ke DB)
        }

        // KIRIM DATA KE VIEW FORM PEMBAYARAN
        return view('hotels.pembayaran', compact('user', 'kamar', 'reservasi')); // Tampilkan form pembayaran
    }

    // ==========================================
    // PROSES PEMBAYARAN & UPLOAD BUKTI TRANSFER
    // ==========================================
    public function prosesPembayaran(Request $request, $id)
    {
        try {
            // LOG DATA REQUEST UNTUK DEBUGGING
            logger()->info('Data yang diterima:', [ // Catat semua data yang masuk
                'request_all' => $request->all(), // Semua input form
                'files' => $request->allFiles(), // File yang diupload
                'id' => $id // ID kamar
            ]);

            // VALIDASI INPUT FORM PEMBAYARAN
            $validated = $request->validate([ // Validasi semua input wajib
                'bank' => 'required|in:mandiri,bca,bri,bni,btn', // Bank harus salah satu dari pilihan
                'atas_nama' => 'nullable|string|max:255', // Nama pemilik rekening (opsional)
                'nomor_rekening' => 'required|string|max:20', // Nomor rekening wajib diisi
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Bukti transfer: gambar, max 2MB
            ]);

        // AMBIL DATA USER, KAMAR & RESERVASI
        $user = Auth::user(); // User yang melakukan pembayaran
        $kamar = Kamar::findOrFail($id); // Kamar yang dipesan

        $reservasi = Reservasi::where('user_id', $user->id) // Cari reservasi user ini
                              ->where('kamar_id', $kamar->id) // Untuk kamar ini
                              ->latest() // Yang paling baru
                              ->firstOrFail(); // Ambil atau error 404 jika tidak ada

        // HITUNG ULANG JUMLAH YANG HARUS DIBAYAR
        $malam = Carbon::parse($reservasi->tanggal_checkin) // Durasi menginap
                    ->diffInDays(Carbon::parse($reservasi->tanggal_checkout)); // Jumlah malam

        $hargaPerMalam = $reservasi->kamar->harga; // Harga per malam dari relasi kamar

        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0; // Kasur tambahan Rp 100.000 (jika ada)

        $jumlahBayar = ($hargaPerMalam * $malam) + $hargaKasur; // Total pembayaran

        // ==========================================
        // UPLOAD & SIMPAN BUKTI TRANSFER
        // ==========================================
        $buktiTransfer = null; // Inisialisasi path file bukti transfer
        if ($request->hasFile('bukti_transfer')) { // Cek apakah ada file yang diupload
            $file = $request->file('bukti_transfer'); // Ambil file dari request
   
            $fileName = 'bukti_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension(); // Nama unik: bukti_timestamp_random.jpg

            $uploadPath = public_path('images'); // Folder tujuan: public/images/
            if (!file_exists($uploadPath)) { // Jika folder images belum ada
                mkdir($uploadPath, 0755, true); // Buat folder dengan permission 755
            }
            
            if ($file->move($uploadPath, $fileName)) { // Pindahkan file ke folder images
                $buktiTransfer = 'images/' . $fileName; // Simpan path relatif untuk database
                logger()->info('Bukti transfer berhasil diupload', ['path' => $buktiTransfer]); // Log sukses upload
            } else {
                throw new \Exception('Gagal mengupload bukti transfer'); // Error jika gagal upload
            }
        } else {
            throw new \Exception('File bukti transfer tidak ditemukan'); // Error jika tidak ada file
        }

        // SIMPAN DATA PEMBAYARAN KE DATABASE
        $pembayaran = Pembayaran::create([ // Buat record pembayaran baru
            'reservasi_id' => $reservasi->id, // ID reservasi yang dibayar
            'tanggal_bayar' => now()->toDateString(), // Tanggal pembayaran (hari ini)
            'jumlah_bayar' => $jumlahBayar, // Total yang dibayar
            'metode_bayar' => 'transfer', // Metode: transfer bank
            'bank' => $validated['bank'], // Bank tujuan transfer
            'atas_nama' => $validated['atas_nama'] ?? null, // Nama pengirim (opsional)
            'bukti_transfer' => $buktiTransfer, // Path file bukti transfer
            'nomor_rekening' => $validated['nomor_rekening'], // Nomor rekening pengirim
            'status_bayar' => 'pending', // Status awal: pending (menunggu verifikasi resepsionis)
        ]);

        // ==========================================
        // GENERATE INVOICE PDF
        // ==========================================
        try {
            // SIAPKAN DATA UNTUK TEMPLATE PDF
            $invoiceData = [ // Data yang akan dikirim ke view PDF
                'reservasi' => $reservasi, // Data reservasi
                'pembayaran' => $pembayaran, // Data pembayaran
                'hotel' => $kamar->hotel, // Data hotel
                'user' => Auth::user() // Data user
            ];

            $pdf = Pdf::loadView('hotels.invoice', $invoiceData); // Generate PDF dari view Blade
            
            // PERSIAPAN FOLDER & FILE PDF
            $invoiceDir = public_path('invoices'); // Folder tujuan: public/invoices/
            if (!file_exists($invoiceDir)) { // Jika folder invoices belum ada
                mkdir($invoiceDir, 0755, true); // Buat folder dengan permission 755
            }

            $filename = 'invoice_' . $reservasi->id . '_' . time() . '_' . Str::random(5) . '.pdf'; // Nama unik: invoice_reservasiID_timestamp_random.pdf
            $pdfPath = public_path('invoices/' . $filename); // Path lengkap file PDF

            logger()->info('Mencoba menyimpan PDF:', [ // Log sebelum menyimpan PDF
                'path' => $pdfPath
            ]);

            $pdf->save($pdfPath); // Simpan PDF ke disk

            logger()->info('PDF berhasil dibuat', [ // Log sukses pembuatan PDF
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            logger()->error('Error saat membuat PDF:', [ // Log jika ada error pembuatan PDF
                'message' => $e->getMessage()
            ]);
            throw new \Exception('Gagal membuat invoice PDF: ' . $e->getMessage()); // Lempar error baru
        }

        // SIMPAN DATA INVOICE KE DATABASE
        $kode_unik = 'INV-' . strtoupper(Str::random(7)); // Generate kode invoice unik: INV-ABC1234

        $invoice = Invoice::create([ // Buat record invoice baru
            'pembayaran_id' => $pembayaran->id, // ID pembayaran terkait
            'tanggal_cetak' => now(), // Tanggal invoice dibuat
            'total' => $jumlahBayar, // Total yang tertera di invoice
            'file_invoice' => 'invoices/' . $filename, // Path file PDF
            'kode_unik' => $kode_unik, // Kode unik invoice
        ]);

        // REDIRECT KE HALAMAN INVOICE DENGAN PESAN SUKSES
        return redirect()->route('invoice.show', ['id' => $invoice->id]) // Ke halaman tampil invoice
                        ->with('success', 'Pembayaran berhasil & invoice dibuat!'); // Flash message sukses

        // ==========================================
        // ERROR HANDLING & LOGGING
        // ==========================================
        } catch (\Exception $e) {
            // LOG ERROR LENGKAP UNTUK DEBUGGING
            logger()->error('Error dalam proses pembayaran:', [ // Catat semua detail error
                'message' => $e->getMessage(), // Pesan error
                'file' => $e->getFile(), // File yang error
                'line' => $e->getLine(), // Baris yang error
                'trace' => $e->getTraceAsString() // Stack trace lengkap
            ]);
            
            // CEK STATUS DIREKTORI UNTUK DEBUGGING
            $imageDir = public_path('images'); // Path folder images
            $invoiceDir = public_path('invoices'); // Path folder invoices
            
            logger()->info('Directory Status:', [ // Log status folder untuk debugging
                'images_exists' => file_exists($imageDir), // Apakah folder images ada?
                'images_writable' => is_writable($imageDir), // Apakah folder images bisa ditulis?
                'invoices_exists' => file_exists($invoiceDir), // Apakah folder invoices ada?
                'invoices_writable' => is_writable($invoiceDir) // Apakah folder invoices bisa ditulis?
            ]);
            
            // KEMBALI KE FORM DENGAN ERROR MESSAGE
            return back() // Kembali ke halaman pembayaran
                ->withErrors(['error' => 'Error: ' . $e->getMessage()]) // Tampilkan pesan error
                ->withInput(); // Kembalikan input user
        }
    }
} 
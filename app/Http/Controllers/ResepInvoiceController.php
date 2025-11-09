<?php

namespace App\Http\Controllers; // Menentukan namespace dari controller ini

use App\Models\Pembayaran; // Mengimpor model Pembayaran
use App\Models\Invoice; // Mengimpor model Invoice
use Illuminate\Http\Request; // Mengimpor class Request untuk menangani input dari user
use Barryvdh\DomPDF\Facade\Pdf; // Mengimpor library DomPDF untuk generate file PDF
use Illuminate\Support\Facades\Storage; // Mengimpor Storage untuk menyimpan file
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal

class ResepInvoiceController extends Controller // Mendefinisikan class controller bernama ResepInvoiceController
{
    // Tampilkan daftar pembayaran (yang pending & success) + fitur search
    public function index(Request $request) // Fungsi untuk menampilkan daftar pembayaran
    {
        $search = $request->input('search'); // Mengambil input pencarian dari request
        $statusFilter = $request->input('status'); // Mengambil input filter status pembayaran

        $pembayarans = Pembayaran::with(['reservasi.user', 'reservasi.kamar.tipeKamar', 'invoice']) // Mengambil data pembayaran beserta relasi terkait
            ->when($statusFilter && $statusFilter !== 'all', function ($query) use ($statusFilter) { // Jika ada filter status selain 'all'
                // Jika ada filter status pembayaran yang dipilih selain 'all', gunakan itu
                $query->where('status_bayar', $statusFilter); // Filter data berdasarkan status bayar
            })
            ->when($search, function ($query, $search) { // Jika ada input pencarian
                $query->whereHas('reservasi.user', function($q) use ($search) { // Cari berdasarkan nama user
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('invoice', function($q) use ($search) { // Atau cari berdasarkan kode invoice
                    $q->where('kode_unik', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc') // Urutkan data dari yang terbaru
            ->get(); // Ambil semua hasil

        return view('resepsionis.invoice.index', compact('pembayarans')); // Tampilkan view dengan data pembayaran
    }

    // Terima pembayaran & buat invoice
    public function accept($id) // Fungsi untuk menerima pembayaran dan membuat invoice
    {
        $pembayaran = Pembayaran::with('reservasi.kamar')->findOrFail($id); // Ambil data pembayaran berdasarkan ID beserta relasinya

        if ($pembayaran->status_bayar === 'lunas') { // Cek jika pembayaran sudah lunas sebelumnya
            return back()->with('error', 'Pembayaran sudah diterima sebelumnya.'); // Kembalikan pesan error
        }

        // Hitung total bayar
        $reservasi = $pembayaran->reservasi; // Ambil data reservasi dari pembayaran
        $malam = Carbon::parse($reservasi->tanggal_checkin) // Hitung jumlah malam menginap
            ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        $hargaPerMalam = $reservasi->kamar->harga; // Ambil harga kamar per malam
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0; // Jika ada kasur tambahan, tambahkan biaya 100000
        $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur; // Hitung total keseluruhan biaya

        // Ubah status pembayaran jadi lunas
        $pembayaran->update([
            'status_bayar' => 'lunas', // Ubah status menjadi lunas
            'harga' => $totalBayar, // Simpan total harga ke database
        ]);

        // Generate kode unik untuk invoice
        $kode_unik = 'INV-' . strtoupper(\Illuminate\Support\Str::random(7)); // Buat kode unik acak untuk invoice

        // Generate invoice PDF
        $pdf = Pdf::loadView('hotels.invoice', compact('reservasi', 'kode_unik')); // Buat file PDF dari view hotels.invoice
        $filename = 'invoice_' . $pembayaran->id . '_' . time() . '.pdf'; // Buat nama file unik berdasarkan ID & waktu
        Storage::put('public/invoices/' . $filename, $pdf->output()); // Simpan file PDF ke storage

        // Simpan ke tabel invoices
        Invoice::create([
            'pembayaran_id' => $pembayaran->id, // ID pembayaran terkait
            'tanggal_cetak' => now(), // Waktu pembuatan invoice
            'total' => $totalBayar, // Total harga yang harus dibayar
            'file_invoice' => 'invoices/' . $filename, // Lokasi file PDF
            'kode_unik' => $kode_unik, // Kode unik invoice
        ]);

        return back()->with('success', 'Pembayaran berhasil diterima & invoice dibuat.'); // Kembalikan pesan sukses
    }

    // Cetak ulang invoice
    public function print($id) // Fungsi untuk mencetak ulang invoice berdasarkan ID
    {
        $invoice = Invoice::with('pembayaran.reservasi.kamar')->findOrFail($id); // Ambil data invoice dengan relasinya
        $reservasi = $invoice->pembayaran->reservasi; // Ambil data reservasi dari pembayaran

        $pdf = Pdf::loadView('hotels.invoice', [ // Generate PDF dari view hotels.invoice
            'reservasi' => $reservasi, // Kirim data reservasi
            'kode_unik' => $invoice->kode_unik, // Kirim kode unik invoice
        ]);
        return $pdf->download('invoice_' . $invoice->id . '.pdf'); // Unduh file PDF dengan nama berdasarkan ID
    }

    // Tolak pembayaran (mark as batal)
    public function reject($id) // Fungsi untuk menolak pembayaran
    {
        $pembayaran = Pembayaran::findOrFail($id); // Ambil data pembayaran berdasarkan ID

        if ($pembayaran->status_bayar !== 'pending') { // Cek jika status bukan pending
            return back()->with('error', 'Hanya pembayaran dengan status pending yang bisa ditolak.'); // Kembalikan pesan error
        }

        $pembayaran->update([
            'status_bayar' => 'batal', // Ubah status menjadi batal
        ]);

        return back()->with('success', 'Pembayaran berhasil ditolak dan status diubah menjadi batal.'); // Kembalikan pesan sukses
    }
}
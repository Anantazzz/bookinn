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
    // Tampilkan daftar pembayaran (yang pending & success) + fitur search
    public function index(Request $request) 
    {
        $search = $request->input('search'); 
        $statusFilter = $request->input('status'); 
        $user = \Illuminate\Support\Facades\Auth::user(); 

        $pembayarans = Pembayaran::with(['reservasi.user', 'reservasi.tamuOffline', 'reservasi.kamar.tipeKamar', 'reservasi.kamar.hotel', 'invoice']) 
            ->whereHas('reservasi.kamar', function($query) use ($user) { 
                if ($user->hotel_id) {
                    $query->where('hotel_id', $user->hotel_id);
                }
            })
            ->when($statusFilter && $statusFilter !== 'all', function ($query) use ($statusFilter) { 
                $query->where('status_bayar', $statusFilter); 
            })
            ->when($search, function ($query, $search) { 
                $query->where(function($q) use ($search) {
                    $q->whereHas('reservasi.user', function($subQ) use ($search) { 
                        $subQ->where('name', 'like', "%{$search}%");
                    })->orWhereHas('reservasi.tamuOffline', function($subQ) use ($search) { 
                        $subQ->where('nama', 'like', "%{$search}%");
                    })->orWhereHas('invoice', function($subQ) use ($search) { 
                        $subQ->where('kode_unik', 'like', "%{$search}%");
                    });
                });
            })
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

        // Hitung total bayar
        $reservasi = $pembayaran->reservasi;
        $malam = Carbon::parse($reservasi->tanggal_checkin) 
            ->diffInDays(Carbon::parse($reservasi->tanggal_checkout));

        $hargaPerMalam = $reservasi->kamar->harga; 
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0; 
        $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;

        // Ubah status pembayaran jadi lunas
        $pembayaran->update([
            'status_bayar' => 'lunas',
            'jumlah_bayar' => $totalBayar,
        ]);

        // Generate kode unik untuk invoice
        $kode_unik = 'INV-' . strtoupper(\Illuminate\Support\Str::random(7)); 

        // Generate invoice PDF
        $pdf = Pdf::loadView('hotels.invoice', compact('reservasi', 'kode_unik')); 
        $filename = 'invoice_' . $pembayaran->id . '_' . time() . '.pdf'; 
        Storage::put('public/invoices/' . $filename, $pdf->output()); 

        // Simpan ke tabel invoices
        Invoice::create([
            'pembayaran_id' => $pembayaran->id,
            'tanggal_cetak' => now(), 
            'total' => $totalBayar, 
            'file_invoice' => 'invoices/' . $filename, 
            'kode_unik' => $kode_unik, 
        ]);

        return back()->with('success', 'Pembayaran berhasil diterima & invoice dibuat.'); 
    }

    // Cetak ulang invoice
    public function print($id) 
    {
        $invoice = Invoice::with('pembayaran.reservasi.kamar')->findOrFail($id); 
        $reservasi = $invoice->pembayaran->reservasi;

        $pdf = Pdf::loadView('hotels.invoice', [ 
            'reservasi' => $reservasi,
            'kode_unik' => $invoice->kode_unik, 
        ]);
        return $pdf->download('invoice_' . $invoice->id . '.pdf'); 
    }

    // Tolak pembayaran (mark as batal)
    public function reject($id) 
    {
        $pembayaran = Pembayaran::findOrFail($id); 
        if ($pembayaran->status_bayar !== 'pending') {
            return back()->with('error', 'Hanya pembayaran dengan status pending yang bisa ditolak.');
        }

        $pembayaran->update([
            'status_bayar' => 'gagal',
        ]);

        return back()->with('success', 'Pembayaran berhasil ditolak dan status diubah menjadi gagal.');
    }
}
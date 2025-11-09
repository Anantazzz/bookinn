<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Kamar;
use App\Models\TipeKamar;
use App\Models\Pembayaran;
use App\Models\TamuOffline;
use App\Models\Invoice;
use Carbon\Carbon;
class ResepOfflineController extends Controller
{
     public function create()
    {
        $tipeKamars = TipeKamar::whereHas('kamars', function($query) {
            $query->where('hotel_id', auth()->user()->hotel_id);
        })->get();
        return view('resepsionis.offline.index', compact('tipeKamars'));
    }
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'no_hp' => 'required|string|min:10|max:15',
                'tipe_kamar_id' => 'required|exists:tipe_kamars,id',
                'tanggal_checkout' => 'required|date|after_or_equal:today',
                'metode_bayar' => 'required|in:Cash,Transfer',
            ]);
        // Simpan tamu offline
        $tamu = TamuOffline::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
        ]);

        // Ambil tipe kamar
        $tipeKamar = TipeKamar::findOrFail($request->tipe_kamar_id);
        
        // Hitung jumlah hari menginap
        $checkinDate = Carbon::now();
        $checkoutDate = Carbon::parse($request->tanggal_checkout);
        $jumlahHari = $checkinDate->diffInDays($checkoutDate);
        
        // Hitung total harga berdasarkan jumlah hari
        $totalHarga = $tipeKamar->harga * $jumlahHari;

        // Cari 1 kamar yang masih tersedia berdasarkan tipe_kamar_id dan hotel_id
        $kamar = Kamar::where('tipe_kamar_id', $tipeKamar->id)
            ->where('hotel_id', auth()->user()->hotel_id)
            ->where('status', 'tersedia')
            ->first();

        // Jika tidak ada kamar tersedia
        if (!$kamar) {
            return back()->with('error', 'Tidak ada kamar tersedia untuk tipe kamar ini.');
        }

        $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0;
        if ($kasurTambahan) {
            $totalHarga += 100000; // Kasur tambahan sekali bayar saja
        }

        // Buat reservasi baru dengan user_id null untuk offline
        $reservasi = Reservasi::create([
            'user_id' => null, // Null untuk reservasi offline
            'kamar_id' => $kamar->id,
            'tanggal_checkin' => Carbon::now()->toDateString(),
            'jam_checkin' => Carbon::now()->format('H:i:s'),
            'tanggal_checkout' => $request->tanggal_checkout,
            'jam_checkout' => '12:00:00',
            'status' => 'aktif',
            'kasur_tambahan' => $kasurTambahan,
            'total_harga' => $totalHarga,
        ]);
        
        // Simpan ID tamu offline ke session untuk digunakan di invoice
        session(['tamu_offline_id' => $tamu->id]);

        // Ubah status kamar jadi "booking"
        $kamar->update(['status' => 'booking']);

        // Buat pembayaran otomatis
        $pembayaran = Pembayaran::create([
            'reservasi_id' => $reservasi->id,
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'jumlah_bayar' => $totalHarga,
            'metode_bayar' => $request->metode_bayar,
            'status_bayar' => 'lunas',
        ]);
        
        // Buat invoice dengan kode unik
        $kodeUnik = 'INV-' . date('Ymd') . '-' . str_pad($reservasi->id, 4, '0', STR_PAD_LEFT);
        Invoice::create([
            'kode_unik' => $kodeUnik,
            'pembayaran_id' => $pembayaran->id,
            'tanggal_cetak' => Carbon::now()->toDateString(),
            'total' => $totalHarga,
            'file_invoice' => 'offline_invoice.pdf',
        ]);

        // Debug: pastikan reservasi tersimpan
        if (!$reservasi->id) {
            return back()->with('error', 'Gagal membuat reservasi');
        }

            return redirect()->route('resepsionis.offline.invoice', $reservasi->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

  public function invoice($id)
{
    $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id);
    $tipeKamar = $reservasi->kamar->tipeKamar;
    $tamuOffline = TamuOffline::find(session('tamu_offline_id'));
    $kasurTambahan = 100000; // Harga kasur tambahan
    
    // Hitung jumlah hari menginap
    $checkinDate = Carbon::parse($reservasi->tanggal_checkin);
    $checkoutDate = Carbon::parse($reservasi->tanggal_checkout);
    $jumlahHari = $checkinDate->diffInDays($checkoutDate);
    
    // Ambil invoice berdasarkan pembayaran_id
    $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id)->first();
    
    return view('resepsionis.offline.invoice', compact('reservasi', 'tipeKamar', 'tamuOffline', 'kasurTambahan', 'invoice', 'jumlahHari'));
}
}

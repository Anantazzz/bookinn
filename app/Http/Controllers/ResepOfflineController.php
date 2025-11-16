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
    // ==========================================
    // TAMPILKAN FORM RESERVASI OFFLINE
    // ==========================================
    public function create() // Fungsi untuk menampilkan form reservasi tamu offline
    {
        // AMBIL TIPE KAMAR YANG TERSEDIA DI HOTEL INI
        $tipeKamars = TipeKamar::whereHas('kamars', function($query) { // Filter tipe kamar yang punya kamar
            $query->where('hotel_id', auth()->user()->hotel_id); // Di hotel resepsionis yang login
        })->get(); // Ambil semua tipe kamar
        
        // SIAPKAN DATA KAMAR DENGAN HARGA
        $kamarData = []; // Array untuk menyimpan data kamar
        foreach ($tipeKamars as $tipe) { // Loop setiap tipe kamar
            $contohKamar = Kamar::where('hotel_id', auth()->user()->hotel_id) // Cari kamar di hotel ini
                ->where('tipe_kamar_id', $tipe->id) // Dengan tipe kamar tertentu
                ->first(); // Ambil satu contoh kamar
                
            if ($contohKamar) { // Jika ada kamar dengan tipe ini
                $kamarData[] = [ // Tambahkan ke array data
                    'tipe_id' => $tipe->id, // ID tipe kamar
                    'nama_tipe' => $tipe->nama_tipe, // Nama tipe kamar
                    'harga' => $contohKamar->harga, // Harga per malam
                ];
            }
        }
        
        return view('resepsionis.offline.index', compact('kamarData')); // Tampilkan form dengan data kamar
    }
    // ==========================================
    // PROSES RESERVASI OFFLINE (CREATE)
    // ==========================================
    public function store(Request $request) // Fungsi untuk memproses reservasi tamu offline
    {
        try {
            // VALIDASI INPUT FORM
            $request->validate([ // Validasi data yang dikirim dari form
                'nama' => 'required|string|max:255', // Nama tamu wajib diisi
                'no_hp' => 'required|string|min:10|max:15', // No HP tamu wajib diisi
                'tipe_kamar_id' => 'required|exists:tipe_kamars,id', // Tipe kamar harus ada di database
                'tanggal_checkout' => 'required|date|after_or_equal:today', // Checkout minimal hari ini
                'metode_bayar' => 'required|in:Cash,Transfer', // Metode bayar hanya Cash atau Transfer
            ]);
        // SIMPAN DATA TAMU OFFLINE
        $tamu = TamuOffline::create([ // Buat record tamu offline baru
            'nama' => $request->nama, // Nama tamu dari form
            'no_hp' => $request->no_hp, // No HP tamu dari form
        ]);

        // CARI KAMAR YANG TERSEDIA
        $kamar = Kamar::where('tipe_kamar_id', $request->tipe_kamar_id) // Filter berdasarkan tipe kamar
            ->where('hotel_id', auth()->user()->hotel_id) // Di hotel resepsionis yang login
            ->where('status', 'tersedia') // Status kamar harus tersedia
            ->first(); // Ambil satu kamar pertama yang tersedia

        // VALIDASI KETERSEDIAAN KAMAR
        if (!$kamar) { // Jika tidak ada kamar tersedia
            return back()->with('error', 'Tidak ada kamar tersedia untuk tipe kamar ini.'); // Kembali dengan error
        }
            
        // HITUNG DURASI MENGINAP DAN TOTAL HARGA
        $checkinDate = Carbon::now('Asia/Jakarta')->startOfDay(); // Checkin hari ini jam 00:00
        $checkoutDate = Carbon::parse($request->tanggal_checkout, 'Asia/Jakarta')->startOfDay(); // Checkout sesuai input jam 00:00
        $jumlahHari = $checkinDate->diffInDays($checkoutDate); // Hitung selisih hari
        
        // HITUNG TOTAL BIAYA
        $totalHarga = $kamar->harga * $jumlahHari; // Harga kamar per malam x jumlah hari
        
        $tipeKamar = $kamar->tipeKamar; // Ambil data tipe kamar

        $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0; // Cek apakah ada kasur tambahan
        if ($kasurTambahan) { // Jika ada kasur tambahan
            $totalHarga += 100000; // Tambah biaya kasur tambahan Rp 100.000 (sekali bayar)
        }

        // BUAT RESERVASI OFFLINE
        $reservasi = Reservasi::create([ // Buat record reservasi baru
            'user_id' => null, // Tidak ada user (reservasi offline)
            'tamu_offline_id' => $tamu->id, // ID tamu offline yang baru dibuat
            'kamar_id' => $kamar->id, // ID kamar yang dipilih
            'tanggal_checkin' => Carbon::now('Asia/Jakarta')->toDateString(), // Checkin hari ini
            'jam_checkin' => Carbon::now('Asia/Jakarta')->format('H:i:s'), // Jam checkin sekarang
            'tanggal_checkout' => $request->tanggal_checkout, // Tanggal checkout dari form
            'jam_checkout' => '12:00:00', // Jam checkout default 12:00
            'status' => 'aktif', // Status reservasi aktif
            'kasur_tambahan' => $kasurTambahan, // Apakah ada kasur tambahan
            'total_harga' => $totalHarga, // Total harga yang sudah dihitung
        ]);
        
        // SIMPAN DATA KE SESSION
        session(['tamu_offline_id' => $tamu->id]); // Simpan ID tamu offline untuk invoice

        // UPDATE STATUS KAMAR MENJADI BOOKING (RESERVASI OFFLINE LANGSUNG AKTIF)
        $kamar->update(['status' => 'booking']); // Ubah status kamar jadi booking
        
        // LOG UNTUK DEBUGGING STATUS KAMAR
        logger()->info('Reservasi offline - Status kamar diupdate:', [
            'kamar_id' => $kamar->id,
            'nomor_kamar' => $kamar->nomor_kamar,
            'status_lama' => 'tersedia',
            'status_baru' => 'booking',
            'reservasi_id' => $reservasi->id,
            'tamu' => $request->nama
        ]);
        
        // REFRESH DATA KAMAR UNTUK MEMASTIKAN UPDATE BERHASIL
        $kamar->refresh();
        logger()->info('Status kamar setelah refresh:', [
            'kamar_id' => $kamar->id,
            'status_aktual' => $kamar->status
        ]);

        // BUAT PEMBAYARAN OTOMATIS (LANGSUNG LUNAS)
        $pembayaran = Pembayaran::create([ // Buat record pembayaran
            'reservasi_id' => $reservasi->id, // ID reservasi yang baru dibuat
            'tanggal_bayar' => Carbon::now('Asia/Jakarta')->toDateString(), // Tanggal bayar hari ini
            'jumlah_bayar' => $totalHarga, // Jumlah yang dibayar
            'metode_bayar' => $request->metode_bayar, // Cash atau Transfer
            'status_bayar' => 'lunas', // Status langsung lunas (offline)
        ]);
        
        // BUAT INVOICE DENGAN KODE UNIK
        $kodeUnik = 'INV-' . date('Ymd') . '-' . str_pad($reservasi->id, 4, '0', STR_PAD_LEFT); // Format: INV-20241201-0001
        Invoice::create([ // Buat record invoice
            'kode_unik' => $kodeUnik, // Kode unik invoice
            'pembayaran_id' => $pembayaran->id, // ID pembayaran terkait
            'tanggal_cetak' => Carbon::now('Asia/Jakarta')->toDateString(), // Tanggal cetak hari ini
            'total' => $totalHarga, // Total harga invoice
            'file_invoice' => 'offline_invoice.pdf', // Nama file PDF
        ]);

        // VALIDASI HASIL RESERVASI
        if (!$reservasi->id) { // Jika reservasi gagal dibuat
            return back()->with('error', 'Gagal membuat reservasi'); // Kembali dengan error
        }

        // REDIRECT KE HALAMAN INVOICE
        return redirect()->route('resepsionis.offline.invoice', $reservasi->id); // Tampilkan invoice
        } catch (\Exception $e) { // Jika terjadi error
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput(); // Kembali dengan error dan input lama
        }
    }

    // ==========================================
    // TAMPILKAN INVOICE RESERVASI OFFLINE
    // ==========================================
    public function invoice($id) // Fungsi untuk menampilkan invoice reservasi offline
    {
        // AMBIL DATA RESERVASI LENGKAP
        $reservasi = Reservasi::with(['kamar.tipeKamar', 'pembayaran'])->findOrFail($id); // Reservasi dengan relasi kamar, tipe kamar, dan pembayaran
        $tipeKamar = $reservasi->kamar->tipeKamar; // Data tipe kamar
        $tamuOffline = TamuOffline::find(session('tamu_offline_id')); // Data tamu offline dari session
        $kasurTambahan = 100000; // Harga kasur tambahan (konstanta)
        
        // HITUNG DURASI MENGINAP
        $checkinDate = Carbon::parse($reservasi->tanggal_checkin); // Parse tanggal checkin
        $checkoutDate = Carbon::parse($reservasi->tanggal_checkout); // Parse tanggal checkout
        $jumlahHari = $checkinDate->diffInDays($checkoutDate); // Hitung selisih hari
        
        // AMBIL DATA INVOICE
        $invoice = Invoice::where('pembayaran_id', $reservasi->pembayaran->id)->first(); // Cari invoice berdasarkan pembayaran ID
        
        // TAMPILKAN INVOICE
        return view('resepsionis.offline.invoice', compact('reservasi', 'tipeKamar', 'tamuOffline', 'kasurTambahan', 'invoice', 'jumlahHari')); // Kirim semua data ke view invoice
    }
}

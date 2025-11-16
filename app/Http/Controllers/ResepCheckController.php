<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\Reservasi; 
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon; 

class ResepCheckController extends Controller 
{
    // Pastikan hanya resepsionis yang bisa akses
    public function __construct() // Konstruktor dijalankan saat class diinisialisasi
    {
        $this->middleware(['auth', 'role:resepsionis']); // Middleware: hanya user yang login dan punya role resepsionis yang bisa mengakses
    }
    
    // Tampilkan daftar reservasi hotel milik resepsionis + search
    public function index(Request $request) // Fungsi untuk menampilkan daftar reservasi dengan filter
    {
        $hotelId = Auth::user()->hotel_id; // Ambil ID hotel dari user yang login
        $search = $request->input('search');  // Ambil input pencarian dari request
        $statusFilter = $request->input('status'); // Ambil filter status dari request

        $reservasis = Reservasi::whereHas('kamar', function($q) use ($hotelId) { // Ambil data reservasi berdasarkan hotel resepsionis
                $q->where('hotel_id', $hotelId); // Hanya ambil kamar yang dimiliki hotel tersebut
            })
            ->when($search, function($query, $search) { // Jika ada input pencarian
                $query->whereHas('user', function($q) use ($search) { // Cari berdasarkan nama user tamu
                    $q->where('name', 'like', "%{$search}%"); // Nama mirip dengan input pencarian
                });
            })
            ->when($statusFilter && $statusFilter !== 'all', function($query) use ($statusFilter) { // Jika filter status digunakan dan bukan 'all'
                $query->where('status', $statusFilter); // Filter data berdasarkan status reservasi
            })
            ->with(['user', 'kamar.tipeKamar']) // Ambil juga relasi user dan tipe kamar
            ->orderBy('id', 'desc') // Urutkan berdasarkan ID secara menurun
            ->get(); // Ambil semua data hasil query

        $now = Carbon::now(); // Ambil waktu sekarang

        foreach ($reservasis as $r) { // Loop semua data reservasi
            $checkinDatetime = null; // Inisialisasi variabel untuk waktu check-in
            $checkoutDatetime = null; // Inisialisasi variabel untuk waktu check-out

            if (isset($r->tanggal_checkin)) { // Jika ada tanggal check-in
                $jamCheckin = $r->jam_checkin ?? '00:00:00'; // Jika tidak ada jam check-in, set jam default 00:00:00
                $checkinDatetime = Carbon::parse($r->tanggal_checkin . ' ' . $jamCheckin); // Gabungkan tanggal dan jam untuk waktu check-in penuh
            }

            if (isset($r->tanggal_checkout)) { // Jika ada tanggal check-out
                $jamCheckout = $r->jam_checkout ?? '00:00:00'; // Jika tidak ada jam check-out, set default
                $checkoutDatetime = Carbon::parse($r->tanggal_checkout . ' ' . $jamCheckout); // Gabungkan tanggal dan jam untuk waktu check-out penuh
            }

            $r->can_checkin = false; // Default: belum bisa check-in
            $r->can_checkout = false; // Default: belum bisa check-out
            $r->status_for_display = $r->status; // Simpan status untuk ditampilkan

            if ($r->status === 'pending') { // Jika status reservasi masih pending
                if ($checkinDatetime && $now->gte($checkinDatetime)) { // Jika waktu sekarang >= waktu check-in
                    $r->can_checkin = true; // Maka bisa dilakukan check-in
                }
            } 
            elseif ($r->status === 'aktif') { // Jika status aktif (tamu sudah check-in)
                $r->can_checkout = true; // Maka bisa dilakukan check-out
            }
        }

        return view('resepsionis.check.index', compact('reservasis', 'search', 'statusFilter')); // Kirim data ke view resepsionis/check/index
    }

    public function updateStatus(Request $request, $id) // Fungsi untuk memperbarui status reservasi
    {
        $request->validate([ // Validasi input status
            'status' => 'required|in:pending,aktif,selesai,batal' // Status hanya boleh salah satu dari nilai ini
        ]);

        $reservasi = Reservasi::with('kamar')->findOrFail($id); // Ambil data reservasi berdasarkan ID beserta relasi kamar

        // Validasi status transisi
        if ($request->status === 'aktif' && $reservasi->status !== 'pending') { // Jika ingin ubah ke aktif tapi sebelumnya bukan pending
            return back()->with('error', 'Hanya reservasi dengan status pending yang bisa di-checkin.'); // Kembalikan error
        }

        if ($request->status === 'selesai' && $reservasi->status !== 'aktif') { // Jika ingin ubah ke selesai tapi sebelumnya bukan aktif
            return back()->with('error', 'Hanya reservasi dengan status aktif yang bisa di-checkout.'); // Kembalikan error
        }

        // Update status reservasi
        $reservasi->status = $request->status; // Ganti status reservasi dengan input baru
        $reservasi->save(); // Simpan perubahan ke database

        // Update otomatis status kamar
        if ($reservasi->kamar) { // Jika reservasi memiliki data kamar
            if ($request->status === 'aktif') { // Jika status menjadi aktif
                // Saat Check-In → kamar jadi booking
                $reservasi->kamar->status = 'booking'; // Ubah status kamar menjadi booking
            } elseif ($request->status === 'selesai') { // Jika status menjadi selesai
                // Saat Check-Out → kamar jadi tersedia
                $reservasi->kamar->status = 'tersedia'; // Ubah status kamar menjadi tersedia
            }
            $reservasi->kamar->save(); // Simpan perubahan status kamar ke database
        }
        
        return back()->with('success', 'Status reservasi & kamar berhasil diperbarui.'); 
    }
}
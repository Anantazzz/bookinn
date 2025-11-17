<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use App\Http\Controllers\DiscountController;

class ReservasiController extends Controller
{
    // ==========================================
    // TAMPILKAN FORM RESERVASI KAMAR
    // ==========================================
    public function showForm(Request $request, $id)
    {
        // AMBIL DATA USER & KAMAR
        $user = Auth::user(); // Data user yang sedang login untuk form
        $kamar = Kamar::findOrFail($id); // Data kamar yang dipilih berdasarkan ID

        // AMBIL TANGGAL DARI PARAMETER URL
        $tanggalCheckin = $request->input('tanggal_checkin'); // Tanggal checkin dari halaman detail hotel
        $tanggalCheckout = $request->input('tanggal_checkout'); // Tanggal checkout dari halaman detail hotel

        // KIRIM DATA KE VIEW FORM RESERVASI
        return view('hotels.reservasi', compact('user', 'kamar', 'tanggalCheckin', 'tanggalCheckout')); // Tampilkan form booking
    }

    // ==========================================
    // PROSES PENYIMPANAN RESERVASI KE DATABASE
    // ==========================================
    public function store(Request $request, $id)
    {
        // PERSIAPAN DATA AWAL
        $user = Auth::user(); // Data user yang melakukan reservasi
        $tipeKamar = Kamar::findOrFail($id); // Data kamar yang dipilih user

        // VALIDASI INPUT FORM RESERVASI
        $request->validate([
            'tanggal_checkin' => 'required|date|after_or_equal:today', // Checkin tidak boleh masa lalu
            'jam_checkin' => 'required', // Jam checkin wajib diisi
            'tanggal_checkout' => 'required|date|after:tanggal_checkin', // Checkout harus setelah checkin
            'jam_checkout' => 'required', // Jam checkout wajib diisi
        ]);

        // SIMPAN TANGGAL UNTUK QUERY KETERSEDIAAN
        $tanggalCheckin = $request->tanggal_checkin; // Tanggal checkin yang dipilih
        $tanggalCheckout = $request->tanggal_checkout; // Tanggal checkout yang dipilih

        // ==========================================
        // CEK KETERSEDIAAN KAMAR BERDASARKAN TIPE
        // ==========================================
        $tipeKamarId = $tipeKamar->tipe_kamar_id; // ID tipe kamar 
        $hotelId = $tipeKamar->hotel_id; // ID hotel tempat kamar berada
        
        // HITUNG TOTAL KAMAR UNTUK TIPE INI DI HOTEL INI
        $totalKamar = Kamar::where('hotel_id', $hotelId)
            ->where('tipe_kamar_id', $tipeKamarId)
            ->count(); 
            
        // HITUNG KAMAR YANG SUDAH DIPESAN PADA TANGGAL TERSEBUT
        $kamarTerpesan = Reservasi::whereHas('kamar', function($q) use ($hotelId, $tipeKamarId) {
                $q->where('hotel_id', $hotelId) // Kamar di hotel ini
                  ->where('tipe_kamar_id', $tipeKamarId); // Kamar tipe ini
            })
            ->whereIn('status', ['pending', 'aktif']) // Hanya reservasi yang masih berlaku
            ->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                // Overlap terjadi jika: checkin_baru < checkout_lama DAN checkout_baru > checkin_lama
                $q->where('tanggal_checkin', '<', $tanggalCheckout) // Checkin reservasi lama sebelum checkout baru
                  ->where('tanggal_checkout', '>', $tanggalCheckin); // Checkout reservasi lama setelah checkin baru
            })
            ->count(); // Jumlah kamar yang bentrok dengan tanggal pilihan

        // VALIDASI: APAKAH MASIH ADA KAMAR TERSEDIA?
        if ($kamarTerpesan >= $totalKamar) {
            return back()->withErrors([
                'tanggal_checkin' => 'Tidak ada kamar tersedia untuk tipe ini pada tanggal tersebut.' // Error jika penuh
            ])->withInput(); // Kembalikan input user
        }
        
        // CARI KAMAR SPESIFIK YANG TERSEDIA UNTUK TIPE INI
        $kamarTersedia = Kamar::where('hotel_id', $hotelId)
            ->where('tipe_kamar_id', $tipeKamarId) // Kamar dengan tipe yang dipilih
            ->where('status', 'tersedia') // Status kamar masih tersedia
            ->whereNotIn('id', function($query) use ($tanggalCheckin, $tanggalCheckout) {
                // EXCLUDE kamar yang sudah dipesan pada tanggal tersebut
                $query->select('kamar_id')
                    ->from('reservasis')
                    ->whereIn('status', ['pending', 'aktif']) // Reservasi aktif
                    ->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                        // LOGIKA OVERLAP YANG BENAR:
                        $q->where('tanggal_checkin', '<', $tanggalCheckout) // Checkin lama < checkout baru
                          ->where('tanggal_checkout', '>', $tanggalCheckin); // Checkout lama > checkin baru
                    });
            })
            ->first(); // Ambil satu kamar yang tersedia
            
        // DOUBLE CHECK: PASTIKAN ADA KAMAR YANG BISA DIPAKAI
        if (!$kamarTersedia) {
            return back()->withErrors([
                'tanggal_checkin' => 'Tidak ada kamar tersedia untuk tipe ini pada tanggal tersebut.' // Error jika tidak ada
            ])->withInput();
        }

        // ==========================================
        // PERHITUNGAN TOTAL HARGA RESERVASI
        // ==========================================
        $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0; // Cek apakah user pilih kasur tambahan
        $jumlahHari = \Carbon\Carbon::parse($tanggalCheckin)->diffInDays(\Carbon\Carbon::parse($tanggalCheckout)); // Hitung jumlah malam
        $hargaKamar = $kamarTersedia->harga * $jumlahHari; // Harga kamar × jumlah malam
        $biayaKasur = $kasurTambahan ? 100000 : 0; // Biaya kasur tambahan Rp 100.000 (sekali bayar)
        $totalHarga = $hargaKamar + $biayaKasur; // Total sebelum diskon
        
        // APLIKASIKAN DISKON JIKA ADA
        $discountAmount = 0;
        $discountCode = null;
        $finalAmount = $totalHarga;
        
        if ($request->filled('discount_code')) {
            // Log untuk debugging
            logger()->info('Validating discount in reservasi', [
                'user_id' => $user->id,
                'code' => $request->discount_code,
                'total_amount' => $totalHarga
            ]);
            
            $discountResult = DiscountController::validateDiscount($request->discount_code, $totalHarga, $user->id);
            
            logger()->info('Discount validation result', $discountResult);
            
            if ($discountResult['valid']) {
                $discountAmount = $discountResult['discount_amount'];
                $discountCode = $discountResult['code'];
                $finalAmount = $discountResult['final_amount'];
                
                // Simpan info diskon ke session
                session([
                    'discount_code' => $discountCode,
                    'discount_amount' => $discountAmount,
                    'discount_name' => $discountResult['name']
                ]);
            } else {
                // Jika kupon tidak valid, kembalikan dengan error
                logger()->warning('Discount validation failed', [
                    'user_id' => $user->id,
                    'code' => $request->discount_code,
                    'message' => $discountResult['message']
                ]);
                
                return back()->withErrors([
                    'discount_code' => $discountResult['message']
                ])->withInput();
            }
        }

        // TENTUKAN STATUS RESERVASI OTOMATIS
        if ($tanggalCheckin == date('Y-m-d')) {
            $status = 'aktif'; // Jika checkin hari ini, langsung aktif
            $statusKamar = 'booking'; // Kamar langsung booking jika checkin hari ini
        } else {
            $status = 'pending'; // Jika checkin nanti, status pending dulu
            $statusKamar = 'tersedia'; // Kamar tetap tersedia sampai tanggal checkin
        }

        // ==========================================
        // SIMPAN RESERVASI & UPDATE STATUS KAMAR
        // ==========================================
        
        // BUAT RECORD RESERVASI BARU DI DATABASE
        $reservasi = Reservasi::create([
            'user_id' => $user->id, // ID user yang melakukan reservasi
            'kamar_id' => $kamarTersedia->id, // ID kamar yang berhasil dialokasikan
            'tanggal_checkin' => $tanggalCheckin, // Tanggal checkin
            'jam_checkin' => $request->jam_checkin, // Jam checkin
            'tanggal_checkout' => $tanggalCheckout, // Tanggal checkout
            'jam_checkout' => $request->jam_checkout, // Jam checkout
            'status' => $status, // Status reservasi (pending/aktif)
            'kasur_tambahan' => $kasurTambahan, // Apakah pakai kasur tambahan (0/1)
            'total_harga' => $finalAmount, // Total harga setelah diskon
        ]);
        
        // UPDATE STATUS KAMAR BERDASARKAN TANGGAL CHECKIN
        $kamarTersedia->update(['status' => $statusKamar]); // Booking jika hari ini, tersedia jika nanti
        
        // LOG UNTUK DEBUGGING (MEMASTIKAN UPDATE BERHASIL)
        logger()->info('Status kamar diupdate', [
            'kamar_id' => $kamarTersedia->id, // ID kamar yang diupdate
            'nomor_kamar' => $kamarTersedia->nomor_kamar, // Nomor kamar
            'status_baru' => $statusKamar, // Status baru (booking/tersedia)
            'tanggal_checkin' => $tanggalCheckin // Tanggal checkin
        ]);

        // SIMPAN ID RESERVASI KE SESSION (UNTUK HALAMAN PEMBAYARAN)
        session(['latest_reservation_id' => $reservasi->id]); // Session untuk tracking reservasi
        
        // REDIRECT KE HALAMAN PEMBAYARAN
        return redirect()->route('hotel.pembayaran', ['id' => $kamarTersedia->id]) // Ke halaman pembayaran
                        ->with('success', 'Reservasi berhasil! Silakan lanjutkan ke pembayaran.'); // Pesan sukses
    }
}

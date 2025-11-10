<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;

class ReservasiController extends Controller
{
    // TAMPILKAN FORM RESERVASI
    public function showForm(Request $request, $id)
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        $kamar = Kamar::findOrFail($id); // Ambil data kamar berdasarkan ID dari URL

        // Ambil tanggal check-in dan check-out yang dikirim dari halaman detail hotel
        $tanggalCheckin = $request->input('tanggal_checkin');
        $tanggalCheckout = $request->input('tanggal_checkout');

        // Kirim data user, kamar, dan tanggal ke halaman form reservasi
        return view('hotels.reservasi', compact('user', 'kamar', 'tanggalCheckin', 'tanggalCheckout'));
    }

    // SIMPAN DATA RESERVASI KE DATABASE
    public function store(Request $request, $id)
    {
        $user = Auth::user(); // Ambil data user login
        $tipeKamar = Kamar::findOrFail($id); // Ambil data tipe kamar berdasarkan ID yang dipilih user

        // Validasi input dari form reservasi
        $request->validate([
            'tanggal_checkin' => 'required|date|after_or_equal:today', // Tidak boleh sebelum hari ini
            'jam_checkin' => 'required', 
            'tanggal_checkout' => 'required|date|after:tanggal_checkin', // Harus setelah check-in
            'jam_checkout' => 'required', 
        ]);

        // Simpan nilai tanggal untuk dipakai di query
        $tanggalCheckin = $request->tanggal_checkin;
        $tanggalCheckout = $request->tanggal_checkout;

        // CEK APAKAH KAMAR YANG DIPILIH TERSEDIA
        $kamarBentrok = Reservasi::where('kamar_id', $id)
            ->whereIn('status', ['pending', 'aktif'])
            ->where(function ($q) use ($tanggalCheckin, $tanggalCheckout) {
                $q->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout])
                  ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout])
                  ->orWhere(function ($r) use ($tanggalCheckin, $tanggalCheckout) {
                      $r->where('tanggal_checkin', '<=', $tanggalCheckin)
                        ->where('tanggal_checkout', '>=', $tanggalCheckout);
                  });
            })
            ->exists();

        // Jika kamar yang dipilih bentrok, tampilkan error
        if ($kamarBentrok) {
            return back()->withErrors([
                'tanggal_checkin' => 'Kamar yang Anda pilih sudah dipesan pada tanggal tersebut.'
            ])->withInput();
        }
        
        // Gunakan kamar yang dipilih user (bukan cari yang lain)
        $kamarTersedia = $tipeKamar; // $tipeKamar sebenarnya adalah kamar yang dipilih

        // HITUNG TOTAL HARGA
        $kasurTambahan = $request->has('kasur_tambahan') ? 1 : 0; 
        $jumlahHari = \Carbon\Carbon::parse($tanggalCheckin)->diffInDays(\Carbon\Carbon::parse($tanggalCheckout));
        $hargaKamar = $kamarTersedia->harga * $jumlahHari; 
        $biayaKasur = $kasurTambahan ? 100000 : 0; 
        $totalHarga = $hargaKamar + $biayaKasur; 

        // TENTUKAN STATUS RESERVASI OTOMATIS
        if ($tanggalCheckin == date('Y-m-d')) {
            $status = 'aktif'; 
        } else {
            $status = 'pending'; 
        }

        // SIMPAN DATA RESERVASI KE DATABASE
        $reservasi = Reservasi::create([
            'user_id' => $user->id, 
            'kamar_id' => $kamarTersedia->id, 
            'tanggal_checkin' => $tanggalCheckin,
            'jam_checkin' => $request->jam_checkin, 
            'tanggal_checkout' => $tanggalCheckout,
            'jam_checkout' => $request->jam_checkout, 
            'status' => $status,
            'kasur_tambahan' => $kasurTambahan, 
            'total_harga' => $totalHarga, 
        ]);
        
        // UPDATE STATUS KAMAR MENJADI BOOKING
        $kamarTersedia->update(['status' => 'booking']);
        
        // Debug: Log untuk memastikan status kamar terupdate
        logger()->info('Status kamar diupdate', [
            'kamar_id' => $kamarTersedia->id,
            'nomor_kamar' => $kamarTersedia->nomor_kamar,
            'status_baru' => 'booking'
        ]);

        // SIMPAN ID RESERVASI TERBARU KE SESSION
        session(['latest_reservation_id' => $reservasi->id]);
        
        // ARAHKAN KE HALAMAN PEMBAYARAN
        return redirect()->route('hotel.pembayaran', ['id' => $kamarTersedia->id])
                        ->with('success', 'Reservasi berhasil! Silakan lanjutkan ke pembayaran.');
    } 
}

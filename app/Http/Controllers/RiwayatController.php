<?php 
namespace App\Http\Controllers; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Reservasi; 
use Illuminate\Http\Request; 

class RiwayatController extends Controller 
{
   public function riwayat() // Mendefinisikan fungsi riwayat() untuk menampilkan daftar riwayat reservasi pengguna
    {
        $reservasis = Reservasi::with(['tipeKamar.hotel', 'pembayaran.invoice']) // Mengambil data reservasi beserta relasi tipe kamar, hotel, pembayaran, dan invoice
            ->where('user_id', Auth::id()) // Menyaring data agar hanya menampilkan reservasi milik user yang sedang login
            ->latest() // Mengurutkan data dari yang terbaru berdasarkan kolom created_at
            ->get(); // Mengambil seluruh hasil query sebagai koleksi data

        return view('hotels.riwayat', compact('reservasis')); // Mengirim data reservasi ke view hotels/riwayat untuk ditampilkan
    }
}
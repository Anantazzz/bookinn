<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Menentukan namespace untuk controller ini
use Illuminate\Support\Facades\Auth; // Mengimpor class Auth untuk mendapatkan data user yang sedang login
use App\Models\Reservasi; // Mengimpor model Reservasi yang berhubungan dengan tabel reservasi di database
use Illuminate\Http\Request; // Mengimpor class Request untuk menangani data dari permintaan (request)

class RiwayatController extends Controller // Mendefinisikan class RiwayatController yang mewarisi class Controller
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
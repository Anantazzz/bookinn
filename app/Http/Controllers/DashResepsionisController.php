<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller
use App\Models\Kamar; // Import model Kamar
use App\Models\Hotel; // Import model Hotel
use Illuminate\Support\Facades\Auth; // Import facade Auth
use Illuminate\Http\Request; // Import Request (tidak selalu dipakai)

class DashResepsionisController extends Controller // Deklarasi class controller untuk dashboard resepsionis
{
    public function index() // Fungsi untuk menampilkan dashboard resepsionis
    {
        $resepsionis = Auth::user(); // Ambil user resepsionis yang sedang login
        $hotel = Hotel::find($resepsionis->hotel_id); // Cari hotel milik resepsionis

    return view('resepsionis.dashboard', compact('hotel')); // Tampilkan view dashboard dengan data hotel

        $kamars = Kamar::with('hotel')->get(); // Ambil semua kamar beserta relasi hotel (baris ini tidak terpakai karena return di atas)
        return view('resepsionis.dashboard', compact('kamars')); // Tampilkan view dashboard dengan data kamar (tidak akan dijalankan)
    }
} 
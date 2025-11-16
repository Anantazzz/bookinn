<?php 
namespace App\Http\Controllers; 
use App\Models\Kamar; 
use App\Models\Hotel; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request; 

class DashResepsionisController extends Controller 
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
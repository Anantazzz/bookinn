<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use Illuminate\Http\Request; // Import Request (jika diperlukan)
use App\Models\Hotel; // Import model Hotel

class HomeController extends Controller // Deklarasi class HomeController
{
     public function index() // Fungsi untuk menampilkan halaman utama
    {
        $hotels = Hotel::all(); // Ambil semua data hotel
        return view('home', compact('hotels')); // Render view home dengan data hotels
    }
} 
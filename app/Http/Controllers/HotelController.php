<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Kamar;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::query();

        //Filter berdasarkan Kota
        if($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        $hotels = $query->paginate(10);
        
        return view('hotels.hotel', compact('hotels'));
    }

    public function show($id)
    {
    // Ambil hotel + semua kamar terkait
    $hotel = Hotel::with('kamars')->findOrFail($id);
    return view('hotels.show', compact('hotel'));
    }
}

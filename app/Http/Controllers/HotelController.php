<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query hotel
        $query = Hotel::with('kamars');

        // Filter kota
        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        // Filter bintang
        if ($request->filled('bintang')) {
            // Pastikan array integer
            $bintangFilter = array_map('intval', (array) $request->bintang);
            $query->whereIn('bintang', $bintangFilter);
        }

        // Filter harga berdasarkan kamar termurah
        if ($request->filled('min_harga') || $request->filled('max_harga')) {
            $minHarga = $request->min_harga ?? 0;
            $maxHarga = $request->max_harga ?? 999999999;

            $query->whereHas('kamars', function($q) use ($minHarga, $maxHarga){
                $q->whereBetween('harga', [$minHarga, $maxHarga]);
            });
        }

        // Ambil semua hasil query
        $hotels = $query->paginate(10); // otomatis pagination

        return view('hotels.hotel', ['hotels' => $hotels]);
    }

    public function detail($id)
    {
        $hotel = Hotel::with('kamars')->findOrFail($id);
        return view('hotels.detail', compact('hotel'));
    }
}

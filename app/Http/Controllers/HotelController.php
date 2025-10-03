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

        if($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        $hotels = $query->paginate(10);

        return view('hotels.hotel', compact('hotels'));
    }

    public function detail($id)
    {
        $hotel = Hotel::with('kamars')->findOrFail($id);
        return view('hotels.detail', compact('hotel'));
    }
}

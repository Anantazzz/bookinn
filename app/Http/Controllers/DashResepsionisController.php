<?php

namespace App\Http\Controllers;
use App\Models\Kamar;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashResepsionisController extends Controller
{
    public function index()
    {
        $resepsionis = Auth::user();
        $hotel = Hotel::find($resepsionis->hotel_id);

    return view('resepsionis.dashboard', compact('hotel'));

        $kamars = Kamar::with('hotel')->get(); 
        return view('resepsionis.dashboard', compact('kamars'));
    }
}

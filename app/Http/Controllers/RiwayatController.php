<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
     public function riwayat()
    {
        $user = Auth::user();

        // Ambil semua reservasi user login
        $reservasis = Reservasi::with(['kamar.hotel', 'kamar.tipeKamar'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        return view('hotels.riwayat', compact('reservasis'));
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function riwayat()
    {
        $reservasis = Reservasi::with(['tipe_kamar.hotel'])->where('user_id', auth()->id())->get();
        return view('hotels.riwayat', compact('reservasis'));
    }
}

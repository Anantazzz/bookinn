<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;

class ReservasiController extends Controller
{
     public function showForm($id)
    {
        $user = Auth::user(); // ambil data user yang login
        $kamar = Kamar::findOrFail($id); // ambil data kamar berdasarkan id

        return view('hotels.reservasi', compact('user', 'kamar'));
    }
}

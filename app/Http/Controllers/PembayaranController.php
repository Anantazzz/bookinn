<?php

namespace App\Http\Controllers;
use App\Models\Reservasi;
use App\Models\Kamar;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
      public function index()
    {
        return view('hotels.pembayaran');
    }

     public function show($id)
    {
        $user = Auth::user(); // ambil data user yang login
        $kamar = Kamar::findOrFail($id); // ambil data kamar berdasarkan id

        return view('hotels.pembayaran', compact('user', 'kamar'));
    }
}

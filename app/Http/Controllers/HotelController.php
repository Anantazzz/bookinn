<?php

namespace App\Http\Controllers;

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
        $hotel = Hotel::findOrFail($id);

        // Ambil semua tipe kamar di hotel ini
        $tipeKamars = \App\Models\TipeKamar::all();

        $kamarData = [];

        foreach ($tipeKamars as $tipe) {
            // Total kamar untuk tipe ini di hotel ini
            $totalKamar = \App\Models\Kamar::where('hotel_id', $hotel->id)
                ->where('tipe_kamar_id', $tipe->id)
                ->count();

            // Jumlah kamar yang sudah dibooking
            $booked = \App\Models\Reservasi::whereHas('kamar', function ($q) use ($hotel, $tipe) {
                    $q->where('hotel_id', $hotel->id)
                    ->where('tipe_kamar_id', $tipe->id);
                })
                ->whereIn('status', ['pending', 'aktif'])
                ->count();

            // Ambil contoh data kamar (buat harga, kapasitas, dll)
            $contohKamar = \App\Models\Kamar::where('hotel_id', $hotel->id)
                ->where('tipe_kamar_id', $tipe->id)
                ->first();

            if ($contohKamar) {
                $kamarData[] = [
                    'id' => $contohKamar->id,
                    'gambar' => $contohKamar->gambar,
                    'tipeKamar' => $tipe->nama_tipe,
                    'harga' => $contohKamar->harga,
                    'kapasitas' => $contohKamar->kapasitas,
                    'jumlahBed' => $contohKamar->jumlah_bed,
                    'internet' => $contohKamar->internet,
                    'sisaKamar' => max(0, $totalKamar - $booked),
                ];
            }
        }

        return view('hotels.detail', compact('hotel', 'kamarData'));
    }
}

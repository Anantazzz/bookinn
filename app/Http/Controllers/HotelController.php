<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Carbon\Carbon;

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
        $hotels = $query->get(); 

        return view('hotels.hotel', ['hotels' => $hotels]);
    }

    public function detail(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        // Ambil tanggal dari request (jika ada)
        $tanggalCheckin = $request->input('tanggal_checkin');
        $tanggalCheckout = $request->input('tanggal_checkout');

        // Validasi tanggal (opsional, bisa dipindah ke form validation)
        if ($tanggalCheckin && $tanggalCheckout) {
            if (Carbon::parse($tanggalCheckout)->lte(Carbon::parse($tanggalCheckin))) {
                return back()->withErrors(['tanggal_checkout' => 'Tanggal check-out harus setelah check-in']);
            }
        }

        // Ambil semua tipe kamar di hotel ini
        $tipeKamars = \App\Models\TipeKamar::all();

        $kamarData = [];

        foreach ($tipeKamars as $tipe) {
            // Total kamar untuk tipe ini di hotel ini
            $totalKamar = \App\Models\Kamar::where('hotel_id', $hotel->id)
                ->where('tipe_kamar_id', $tipe->id)
                ->count();

            // PERBAIKAN: Hitung kamar yang terpesan HANYA pada range tanggal tertentu
            $booked = 0;
            
            if ($tanggalCheckin && $tanggalCheckout) {
                // Cek overlap booking pada range tanggal yang dipilih
                $booked = \App\Models\Reservasi::whereHas('kamar', function ($q) use ($hotel, $tipe) {
                        $q->where('hotel_id', $hotel->id)
                          ->where('tipe_kamar_id', $tipe->id);
                    })
                    ->whereIn('status', ['pending', 'aktif'])
                    ->where(function($query) use ($tanggalCheckin, $tanggalCheckout) {
                        // Logika overlap:
                        // Booking overlap jika:
                        // 1. Check-in booking lama di antara range baru
                        // 2. Check-out booking lama di antara range baru  
                        // 3. Booking lama "menyelimuti" range baru
                        $query->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout])
                              ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout])
                              ->orWhere(function($q) use ($tanggalCheckin, $tanggalCheckout) {
                                  $q->where('tanggal_checkin', '<=', $tanggalCheckin)
                                    ->where('tanggal_checkout', '>=', $tanggalCheckout);
                              });
                    })
                    ->count();
           } else {
                // ✅ Jika tidak ada filter tanggal, tampilkan semua kamar tersedia
                $booked = 0;
            }

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

        // Pastikan variabel selalu ada (meskipun null)
        return view('hotels.detail', [
            'hotel' => $hotel,
            'kamarData' => $kamarData,
            'tanggalCheckin' => $tanggalCheckin,
            'tanggalCheckout' => $tanggalCheckout
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\TipeKamar;
use App\Models\Kamar;
use App\Models\Reservasi;
use Carbon\Carbon;

class HotelController extends Controller 
{
    public function index(Request $request)
    {
        $query = Hotel::with('kamars');

        if ($request->filled('kota')) {
            $query->where('kota', $request->input('kota'));
        }

        if ($request->filled('bintang')) {
            $bintangFilter = array_map('intval', (array) $request->input('bintang'));
            $query->whereIn('bintang', $bintangFilter);
        }

        if ($request->filled('min_harga') || $request->filled('max_harga')) {
            $minHarga = (int) ($request->input('min_harga') ?? 0);
            $maxHarga = (int) ($request->input('max_harga') ?? 999999999);

            $query->whereHas('kamars', function($q) use ($minHarga, $maxHarga){
                $q->whereBetween('harga', [$minHarga, $maxHarga]);
            });
        }

        $hotels = $query->get();

        return view('hotels.hotel', compact('hotels'));
    }

    public function detail(Request $request, $id)
    {
        try {
            $hotel = Hotel::findOrFail((int) $id);
        } catch (\Exception $e) {
            return redirect()->route('hotels.index')->withErrors(['error' => 'Hotel tidak ditemukan']);
        }

        $tanggalCheckin = $request->input('tanggal_checkin');
        $tanggalCheckout = $request->input('tanggal_checkout');

        if ($tanggalCheckin && $tanggalCheckout) {
            try {
                if (Carbon::parse($tanggalCheckout)->lte(Carbon::parse($tanggalCheckin))) {
                    return back()->withErrors(['tanggal_checkout' => 'Tanggal check-out harus setelah check-in']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['tanggal' => 'Format tanggal tidak valid']);
            }
        }

        /** @var \Illuminate\Database\Eloquent\Collection<TipeKamar> $tipeKamars */
        $tipeKamars = TipeKamar::all();
        $kamarData = [];

        /** @var TipeKamar $tipe */
        foreach ($tipeKamars as $tipe) {
            $totalKamar = Kamar::where('hotel_id', (int) $hotel->id)
                ->where('tipe_kamar_id', (int) $tipe->id)
                ->count();

            $booked = 0;
            
            if ($tanggalCheckin && $tanggalCheckout) {
                $booked = Reservasi::whereHas('kamar', function ($q) use ($hotel, $tipe) {
                        $q->where('hotel_id', (int) $hotel->id)
                          ->where('tipe_kamar_id', (int) $tipe->id);
                    })
                    ->whereIn('status', ['pending', 'aktif'])
                    ->where(function($query) use ($tanggalCheckin, $tanggalCheckout) {
                        $query->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout])
                              ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout])
                              ->orWhere(function($q) use ($tanggalCheckin, $tanggalCheckout) {
                                  $q->where('tanggal_checkin', '<=', $tanggalCheckin)
                                    ->where('tanggal_checkout', '>=', $tanggalCheckout);
                              });
                    })
                    ->count();
            }

            $contohKamar = Kamar::where('hotel_id', (int) $hotel->id)
                ->where('tipe_kamar_id', (int) $tipe->id)
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

        return view('hotels.detail', compact('hotel', 'kamarData', 'tanggalCheckin', 'tanggalCheckout'));
    }
} 
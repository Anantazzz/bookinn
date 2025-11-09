<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use Illuminate\Http\Request; // Import Request
use App\Models\Hotel; // Import model Hotel
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class HotelController extends Controller // Deklarasi class HotelController
{
    public function index(Request $request) // Fungsi index untuk daftar hotel
    {
        // Mulai query hotel
        $query = Hotel::with('kamars'); // Query dengan relasi kamars

        // Filter kota
        if ($request->filled('kota')) { // Jika parameter kota diisi
            $query->where('kota', $request->kota); // Filter berdasarkan kota
        }

        // Filter bintang
        if ($request->filled('bintang')) { // Jika parameter bintang diisi
            // Pastikan array integer
            $bintangFilter = array_map('intval', (array) $request->bintang); // Konversi ke integer
            $query->whereIn('bintang', $bintangFilter); // Filter bintang
        }

        // Filter harga berdasarkan kamar termurah
        if ($request->filled('min_harga') || $request->filled('max_harga')) { // Jika ada filter harga
            $minHarga = $request->min_harga ?? 0; // Default min harga
            $maxHarga = $request->max_harga ?? 999999999; // Default max harga sangat besar

            $query->whereHas('kamars', function($q) use ($minHarga, $maxHarga){ // Filter relasi kamars berdasarkan harga
                $q->whereBetween('harga', [$minHarga, $maxHarga]); // Kondisi harga antara min dan max
            });
        }

        // Ambil semua hasil query
        $hotels = $query->get(); // Eksekusi query

        return view('hotels.hotel', ['hotels' => $hotels]); // Render view dengan data hotels
    }

    public function detail(Request $request, $id) // Fungsi detail untuk halaman hotel tertentu
    {
        $hotel = Hotel::findOrFail($id); // Cari hotel atau 404

        // Ambil tanggal dari request (jika ada)
        $tanggalCheckin = $request->input('tanggal_checkin'); // Tanggal check-in pilihan user
        $tanggalCheckout = $request->input('tanggal_checkout'); // Tanggal check-out pilihan user

        // Validasi tanggal (opsional, bisa dipindah ke form validation)
        if ($tanggalCheckin && $tanggalCheckout) { // Jika kedua tanggal ada
            if (Carbon::parse($tanggalCheckout)->lte(Carbon::parse($tanggalCheckin))) { // Jika checkout <= checkin
                return back()->withErrors(['tanggal_checkout' => 'Tanggal check-out harus setelah check-in']); // Kembalikan error
            }
        }

        // Ambil semua tipe kamar di hotel ini
        $tipeKamars = \App\Models\TipeKamar::all(); // Ambil semua tipe kamar

        $kamarData = []; // Inisialisasi array untuk menyimpan data kamar

        foreach ($tipeKamars as $tipe) { // Loop setiap tipe kamar
            // Total kamar untuk tipe ini di hotel ini
            $totalKamar = \App\Models\Kamar::where('hotel_id', $hotel->id) // Query jumlah kamar
                ->where('tipe_kamar_id', $tipe->id) // Filter berdasarkan tipe
                ->count(); // Hitung jumlah

            $booked = 0; // Inisialisasi jumlah yang ter-book
            
            if ($tanggalCheckin && $tanggalCheckout) { // Jika tanggal dipilih
                // Cek overlap booking pada range tanggal yang dipilih
                $booked = \App\Models\Reservasi::whereHas('kamar', function ($q) use ($hotel, $tipe) { // Reservasi terkait kamar hotel & tipe
                        $q->where('hotel_id', $hotel->id) // Filter hotel
                          ->where('tipe_kamar_id', $tipe->id); // Filter tipe kamar
                    })
                    ->whereIn('status', ['pending', 'aktif']) // Hanya status pending atau aktif
                    ->where(function($query) use ($tanggalCheckin, $tanggalCheckout) { // Kondisi overlap booking
                        // Logika overlap:
                        // Booking overlap jika:
                        // 1. Check-in booking lama di antara range baru
                        // 2. Check-out booking lama di antara range baru  
                        // 3. Booking lama "menyelimuti" range baru
                        $query->whereBetween('tanggal_checkin', [$tanggalCheckin, $tanggalCheckout]) // Kondisi 1
                              ->orWhereBetween('tanggal_checkout', [$tanggalCheckin, $tanggalCheckout]) // Kondisi 2
                              ->orWhere(function($q) use ($tanggalCheckin, $tanggalCheckout) { // Kondisi 3
                                  $q->where('tanggal_checkin', '<=', $tanggalCheckin) // Check-in lama <= checkin baru
                                    ->where('tanggal_checkout', '>=', $tanggalCheckout); // Check-out lama >= checkout baru
                              });
                    })
                    ->count(); // Hitung jumlah reservasi overlap
           } else {
                $booked = 0; // Jika tidak memilih tanggal, booked 0
            }

            // Ambil contoh data kamar (buat harga, kapasitas, dll)
            $contohKamar = \App\Models\Kamar::where('hotel_id', $hotel->id) // Query contoh kamar
                ->where('tipe_kamar_id', $tipe->id) // Filter berdasarkan tipe
                ->first(); // Ambil satu contoh

            if ($contohKamar) { // Jika ada contoh kamar
                $kamarData[] = [ // Tambahkan data kamar ke array
                    'id' => $contohKamar->id, // ID kamar
                    'gambar' => $contohKamar->gambar, // Gambar kamar
                    'tipeKamar' => $tipe->nama_tipe, // Nama tipe kamar
                    'harga' => $contohKamar->harga, // Harga kamar
                    'kapasitas' => $contohKamar->kapasitas, // Kapasitas kamar
                    'jumlahBed' => $contohKamar->jumlah_bed, // Jumlah tempat tidur
                    'internet' => $contohKamar->internet, // Status internet
                    'sisaKamar' => max(0, $totalKamar - $booked), // Sisa kamar (tidak negatif)
                ];
            }
        }

        // Pastikan variabel selalu ada (meskipun null)
        return view('hotels.detail', [ // Render view detail hotel dengan data
            'hotel' => $hotel, // Data hotel
            'kamarData' => $kamarData, // Data kamar per tipe
            'tanggalCheckin' => $tanggalCheckin, // Tanggal check-in
            'tanggalCheckout' => $tanggalCheckout // Tanggal check-out
        ]);
    }
} 
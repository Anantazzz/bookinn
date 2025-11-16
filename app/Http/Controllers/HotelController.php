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
    // ==========================================
    // HALAMAN DAFTAR HOTEL (PENCARIAN & FILTER)
    // ==========================================
    public function index(Request $request)
    {
        // Mulai query hotel dengan relasi kamars
        $query = Hotel::with('kamars');

        // FILTER BERDASARKAN KOTA
        if ($request->filled('kota')) {
            $query->where('kota', $request->input('kota')); // Filter hotel berdasarkan kota yang dipilih
        }

        // FILTER BERDASARKAN BINTANG HOTEL
        if ($request->filled('bintang')) {
            $bintangFilter = array_map('intval', (array) $request->input('bintang')); // Konversi ke array integer
            $query->whereIn('bintang', $bintangFilter); // Filter hotel berdasarkan rating bintang
        }

        // FILTER BERDASARKAN RANGE HARGA KAMAR
        if ($request->filled('min_harga') || $request->filled('max_harga')) {
            $minHarga = (int) ($request->input('min_harga') ?? 0); // Harga minimum, default 0
            $maxHarga = (int) ($request->input('max_harga') ?? 999999999); // Harga maksimum, default sangat besar

            $query->whereHas('kamars', function($q) use ($minHarga, $maxHarga){
                $q->whereBetween('harga', [$minHarga, $maxHarga]); // Cari hotel yang punya kamar dalam range harga
            });
        }

        // EKSEKUSI QUERY DAN AMBIL HASIL
        $hotels = $query->get(); // Ambil semua hotel yang sesuai filter

        // KIRIM DATA KE VIEW
        return view('hotels.hotel', compact('hotels')); // Tampilkan halaman daftar hotel
    }

    // ==========================================
    // HALAMAN DETAIL HOTEL & KETERSEDIAAN KAMAR
    // ==========================================
    public function detail(Request $request, $id)
    {
        // VALIDASI & AMBIL DATA HOTEL
        try {
            $hotel = Hotel::findOrFail((int) $id); // Cari hotel berdasarkan ID, error jika tidak ada
        } catch (\Exception $e) {
            return redirect()->route('hotels.index')->withErrors(['error' => 'Hotel tidak ditemukan']); // Redirect jika hotel tidak ditemukan
        }

        // AMBIL TANGGAL DARI REQUEST (UNTUK CEK KETERSEDIAAN)
        $tanggalCheckin = $request->input('tanggal_checkin'); // Tanggal check-in yang dipilih user
        $tanggalCheckout = $request->input('tanggal_checkout'); // Tanggal check-out yang dipilih user

        // VALIDASI TANGGAL (JIKA ADA)
        if ($tanggalCheckin && $tanggalCheckout) {
            try {
                if (Carbon::parse($tanggalCheckout)->lte(Carbon::parse($tanggalCheckin))) {
                    return back()->withErrors(['tanggal_checkout' => 'Tanggal check-out harus setelah check-in']); // Error jika checkout <= checkin
                }
            } catch (\Exception $e) {
                return back()->withErrors(['tanggal' => 'Format tanggal tidak valid']); // Error jika format tanggal salah
            }
        }

        // PERSIAPAN DATA KAMAR PER TIPE
        /** @var \Illuminate\Database\Eloquent\\Collection<TipeKamar> $tipeKamars */
        $tipeKamars = TipeKamar::all(); // Ambil semua tipe kamar yang ada
        $kamarData = []; // Array untuk menyimpan data kamar per tipe

        // LOOP SETIAP TIPE KAMAR UNTUK CEK KETERSEDIAAN
        /** @var TipeKamar $tipe */
        foreach ($tipeKamars as $tipe) {
            // HITUNG TOTAL KAMAR UNTUK TIPE INI DI HOTEL INI
            $totalKamar = Kamar::where('hotel_id', (int) $hotel->id)
                ->where('tipe_kamar_id', (int) $tipe->id)
                ->count(); // Jumlah total kamar tipe ini di hotel

            // HITUNG KAMAR YANG SUDAH DIPESAN (JIKA ADA TANGGAL)
            $booked = 0; // Default: tidak ada yang dipesan
            
            if ($tanggalCheckin && $tanggalCheckout) {
                // Cari reservasi yang bentrok dengan tanggal yang dipilih
                $booked = Reservasi::whereHas('kamar', function ($q) use ($hotel, $tipe) {
                        $q->where('hotel_id', (int) $hotel->id) // Kamar di hotel ini
                          ->where('tipe_kamar_id', (int) $tipe->id); // Kamar tipe ini
                    })
                    ->whereIn('status', ['pending', 'aktif']) // Hanya reservasi aktif/pending
                    ->where(function($query) use ($tanggalCheckin, $tanggalCheckout) {
                        // LOGIKA OVERLAP TANGGAL YANG BENAR:
                        // Overlap terjadi jika: checkin_baru < checkout_lama DAN checkout_baru > checkin_lama
                        $query->where('tanggal_checkin', '<', $tanggalCheckout) // Checkin lama < checkout baru
                              ->where('tanggal_checkout', '>', $tanggalCheckin); // Checkout lama > checkin baru
                    })
                    ->count(); // Hitung jumlah kamar yang bentrok
            }

            // AMBIL CONTOH KAMAR UNTUK DATA HARGA & FASILITAS
            $contohKamar = Kamar::where('hotel_id', (int) $hotel->id)
                ->where('tipe_kamar_id', (int) $tipe->id)
                ->first(); // Ambil satu kamar sebagai contoh

            // SUSUN DATA KAMAR JIKA ADA CONTOH KAMAR
            if ($contohKamar) {
                $kamarData[] = [
                    'id' => $contohKamar->id, // ID kamar untuk booking
                    'gambar' => $contohKamar->gambar, // Foto kamar
                    'tipeKamar' => $tipe->nama_tipe, // Nama tipe (Standard, Deluxe, dll)
                    'harga' => $contohKamar->harga, // Harga per malam
                    'kapasitas' => $contohKamar->kapasitas, // Jumlah orang yang bisa menginap
                    'jumlahBed' => $contohKamar->jumlah_bed, // Jumlah tempat tidur
                    'internet' => $contohKamar->internet, // Ada WiFi atau tidak
                    'sisaKamar' => max(0, $totalKamar - $booked), // Sisa kamar tersedia (tidak boleh negatif)
                ];
            }
        }

        // KIRIM DATA KE VIEW DETAIL HOTEL
        return view('hotels.detail', compact('hotel', 'kamarData', 'tanggalCheckin', 'tanggalCheckout'));
    }
} 
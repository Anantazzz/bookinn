<?php 

namespace App\Http\Controllers; 

use App\Models\Hotel; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
class AdminHotelController extends Controller 
{
    // ==================
    // DASHBOARD ADMIN 
    // ==================
    public function dashboard() // Fungsi untuk menampilkan dashboard admin
    {
        $totalHotel = Hotel::count(); // Menghitung total hotel
        $totalKota = Hotel::select('kota')->distinct()->count(); // Menghitung total kota unik
        return view('admin.dashboard', compact('totalHotel', 'totalKota')); // Menampilkan view dashboard dengan data
    }

    // ==========================================
    // TAMPILKAN DAFTAR HOTEL (READ)
    // ==========================================
    public function index(Request $request) // Fungsi untuk menampilkan daftar hotel
    {
        $query = Hotel::query(); // Membuat query hotel

        if ($request->has('kota') && $request->kota != '') { // Jika ada filter kota
            $query->where('kota', $request->kota); // Filter berdasarkan kota
        }

        $hotels = $query->get(); // Mengambil data hotel
        $kotas = Hotel::select('kota')->distinct()->pluck('kota'); // Mengambil daftar kota unik

        return view('admin.hotels.index', compact('hotels', 'kotas')); // Menampilkan view index hotel
    }

    // ==========================================
    // TAMPILKAN DETAIL HOTEL (READ)
    // ==========================================
    public function show($id) // Fungsi untuk menampilkan detail hotel
    {
        $hotel = Hotel::findOrFail($id); // Cari hotel berdasarkan id
        return view('admin.hotels.show', compact('hotel')); // Tampilkan view detail hotel
    }

    // ==========================================
    // TAMBAH DATA HOTEL BARU (CREATE)
    // ==========================================
    public function store(Request $request) // Fungsi untuk menambah data hotel
    {
        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255', // Validasi nama hotel
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Validasi gambar
            'kota' => 'required|string|max:255', // Validasi kota
            'alamat' => 'required|string|max:255', // Validasi alamat
            'bintang' => 'required|integer|min:1|max:5', // Validasi bintang hotel
            'norek' => 'required|string|max:30', // Validasi nomor rekening
        ]);

        if ($request->hasFile('gambar')) { // Jika ada file gambar diupload
            $file = $request->file('gambar'); // Ambil file gambar
            $filename = time() . '_' . $file->getClientOriginalName(); // Buat nama file unik
            $file->move(public_path('images'), $filename); // Pindahkan file ke folder images
            $validated['gambar'] = $filename; // Simpan nama file ke data
        }

        Hotel::create($validated); // Simpan data hotel ke database

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel berhasil ditambahkan.'); // Redirect ke halaman index hotel dengan pesan sukses
    }

    // ==========================================
    // TAMPILKAN FORM EDIT HOTEL (UPDATE)
    // ==========================================
    public function edit($id) // Fungsi untuk menampilkan form edit hotel
    {
        $hotel = Hotel::findOrFail($id); // Cari hotel berdasarkan id
        return view('admin.hotels.edit', compact('hotel')); // Tampilkan view edit hotel
    }

    // ==========================================
    // PROSES UPDATE DATA HOTEL (UPDATE)
    // ==========================================
    public function update(Request $request, $id) // Fungsi untuk mengupdate data hotel
    {
        $hotel = Hotel::findOrFail($id); // Cari hotel berdasarkan id

        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255', // Validasi nama hotel
            'kota' => 'required|string|max:255', // Validasi kota
            'alamat' => 'required|string|max:255', // Validasi alamat
            'bintang' => 'required|integer|min:1|max:5', // Validasi bintang hotel
            'norek' => 'required|string|max:30', // Validasi nomor rekening
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Validasi gambar
        ]);

        if ($request->hasFile('gambar')) { // Jika ada file gambar diupload
            //hapus file lama kalau ada
            if ($hotel->gambar && file_exists(public_path('images/' . $hotel->gambar))) { // Jika ada gambar lama
                unlink(public_path('images/' . $hotel->gambar)); // Hapus gambar lama
            }

            $file = $request->file('gambar'); // Ambil file gambar baru
            $filename = time() . '_' . $file->getClientOriginalName(); // Buat nama file unik
            $file->move(public_path('images'), $filename); // Pindahkan file ke folder images
            $validated['gambar'] = $filename; // Simpan nama file ke data
        }

        $hotel->update($validated); // Update data hotel

        return redirect()->route('admin.hotels.index')->with('success', 'Data hotel berhasil diperbarui.'); // Redirect ke halaman index hotel dengan pesan sukses
    }

    // ==========================================
    // HAPUS DATA HOTEL (DELETE)
    // ==========================================
    public function destroy($id) // Fungsi untuk menghapus data hotel
    {
        $hotel = Hotel::findOrFail($id); // Cari hotel berdasarkan id

        if ($hotel->gambar && file_exists(public_path('images/' . $hotel->gambar))) { // Jika ada gambar hotel
            unlink(public_path('images/' . $hotel->gambar)); // Hapus gambar hotel
        }

        $hotel->delete(); // Hapus data hotel dari database

        return redirect()->route('admin.hotels.index')->with('success', 'Data hotel berhasil dihapus.'); // Redirect ke halaman index hotel dengan pesan sukses
    }
}

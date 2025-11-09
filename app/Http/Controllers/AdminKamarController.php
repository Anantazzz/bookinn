<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use App\Models\Kamar; // Import model Kamar
use App\Models\Hotel; // Import model Hotel
use App\Models\TipeKamar; // Import model TipeKamar
use Illuminate\Http\Request; // Import Request

class AdminKamarController extends Controller // Deklarasi class controller
{
   public function index(Request $request) // Fungsi untuk menampilkan daftar kamar
    {
        $query = Kamar::with('hotel', 'tipeKamar'); // Query kamar beserta relasi hotel dan tipe kamar

        if ($request->has('hotel_id')) { // Jika ada filter hotel_id
            $query->where('hotel_id', $request->hotel_id); // Filter berdasarkan hotel_id
        }

        $kamars = $query->get(); // Ambil data kamar
        $hotels = Hotel::all(); // Ambil semua data hotel

        return view('admin.kamars.index', compact('kamars', 'hotels')); // Tampilkan view index kamar
    }

    public function store(Request $request) // Fungsi untuk menambah data kamar
    {
    $validated = $request->validate([
        'hotel_id' => 'required|exists:hotels,id', // Validasi hotel_id
        'nomor_kamar' => 'required|string|max:255|unique:kamars,nomor_kamar', // Validasi nomor kamar unik
        'harga' => 'required|numeric|min:0', // Validasi harga
        'kapasitas' => 'required|integer|min:1', // Validasi kapasitas
        'jumlah_bed' => 'required|integer|min:1', // Validasi jumlah bed
        'internet' => 'required|boolean', // Validasi internet
        'status' => 'required|in:tersedia,booking', // Validasi status
        'tipe_kamar_id' => 'required|exists:tipe_kamars,id', // Validasi tipe kamar
        'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:102400', // Validasi gambar
    ]);

    if ($request->hasFile('gambar')) { // Jika ada file gambar diupload
        $file = $request->file('gambar'); // Ambil file gambar
        $filename = time() . '_' . $file->getClientOriginalName(); // Buat nama file unik

        // pindahkan ke public/images/kamars
        $file->move(public_path('images/kamars'), $filename); // Pindahkan file ke folder images/kamars

        // simpan nama file-nya aja
        $validated['gambar'] = $filename; // Simpan nama file ke data
    }

    Kamar::create($validated); // Simpan data kamar ke database

    return redirect()->route('admin.kamars.index')->with('success', 'Kamar berhasil ditambahkan!'); // Redirect ke halaman index kamar dengan pesan sukses
    }
    
    public function show($id) // Fungsi untuk menampilkan detail kamar
    {
        $kamar = Kamar::with(['hotel', 'tipeKamar'])->findOrFail($id); // Cari kamar beserta relasi hotel dan tipe kamar

        return view('admin.kamars.show', compact('kamar')); // Tampilkan view detail kamar
    }


    public function edit($id) // Fungsi untuk menampilkan form edit kamar
    {
        $kamar = Kamar::findOrFail($id); // Cari kamar berdasarkan id
        $hotels = Hotel::all(); // Ambil semua data hotel
        $tipeKamars = TipeKamar::all(); // Ambil semua data tipe kamar

        $imageDir = public_path('images/kamars'); // Path folder gambar kamar
        $imageFiles = is_dir($imageDir) ? array_diff(scandir($imageDir), ['.', '..']) : []; // Ambil daftar file gambar

        return view('admin.kamars.edit', compact('kamar', 'hotels', 'tipeKamars', 'imageFiles')); // Tampilkan view edit kamar
    }

    public function update(Request $request, $id) // Fungsi untuk mengupdate data kamar
    {
        $kamar = Kamar::findOrFail($id); // Cari kamar berdasarkan id

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id', // Validasi hotel_id
            'tipe_kamar_id' => 'required|exists:tipe_kamars,id', // Validasi tipe kamar
            'nomor_kamar' => 'required|string|unique:kamars,nomor_kamar,' . $id, // Validasi nomor kamar unik kecuali id ini
            'harga' => 'required|numeric|min:0', // Validasi harga
            'kapasitas' => 'required|integer|min:1', // Validasi kapasitas
            'jumlah_bed' => 'required|integer|min:1', // Validasi jumlah bed
            'status' => 'required|in:tersedia,booking', // Validasi status
            'internet' => 'boolean', // Validasi internet
            'gambar' => 'nullable|string' // Validasi gambar (opsional)
        ]);

        // simpan path gambar dengan folder-nya
        if (!empty($validated['gambar'])) { // Jika ada gambar
            $validated['gambar'] = 'images/kamars/' . $validated['gambar']; // Simpan path gambar
        }

        $kamar->update($validated); // Update data kamar

        return redirect()->route('admin.kamars.index')->with('success', 'Data kamar berhasil diperbarui!'); // Redirect ke halaman index kamar dengan pesan sukses
    }

    public function destroy($id) // Fungsi untuk menghapus data kamar
    {
        $kamar = Kamar::findOrFail($id); // Cari kamar berdasarkan id
        $kamar->delete(); // Hapus data kamar dari database

        return redirect()->route('admin.kamars.index')->with('success', 'Data kamar berhasil dihapus!'); // Redirect ke halaman index kamar dengan pesan sukses
    }
} 
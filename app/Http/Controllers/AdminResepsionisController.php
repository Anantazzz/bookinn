<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use App\Models\User; // Import model User
use App\Models\Hotel; // Import model Hotel
use Illuminate\Http\Request; // Import Request

class AdminResepsionisController extends Controller // Deklarasi class controller
{
    public function index() // Fungsi untuk menampilkan daftar resepsionis
    {
        $resepsionis = User::with('hotel') // Ambil data user beserta relasi hotel
            ->where('role', 'resepsionis') // Filter role resepsionis
            ->get(); // Ambil data

        $hotels = Hotel::all(); // Ambil semua data hotel

        return view('admin.resepsionis.index', compact('resepsionis', 'hotels')); // Tampilkan view index resepsionis
    }

    public function store(Request $request) // Fungsi untuk menambah data resepsionis
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Validasi nama
            'email' => 'required|email|unique:users,email', // Validasi email unik
            'password' => 'required|string|min:6', // Validasi password
            'alamat' => 'nullable|string|max:255', // Validasi alamat (opsional)
            'no_hp' => 'nullable|string|max:15', // Validasi no hp (opsional)
            'hotel_id' => 'required|exists:hotels,id', // Validasi hotel_id
            'shift' => 'nullable|in:pagi,malam', // Validasi shift (opsional)
        ]);

        User::create([
            'name' => $validated['name'], // Simpan nama
            'email' => $validated['email'], // Simpan email
            'password' => bcrypt($validated['password']), // Simpan password terenkripsi
            'alamat' => $validated['alamat'] ?? null, // Simpan alamat jika ada
            'no_hp' => $validated['no_hp'] ?? null, // Simpan no hp jika ada
            'role' => 'resepsionis', // Set role resepsionis
            'hotel_id' => $validated['hotel_id'], // Simpan hotel_id
            'shift' => $validated['shift'], // Simpan shift
        ]);

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Data resepsionis berhasil ditambahkan!'); // Redirect ke halaman index resepsionis dengan pesan sukses
    }

    public function show($id) // Fungsi untuk menampilkan detail resepsionis
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id); // Cari resepsionis berdasarkan id

        // Ambil data non-database dari session
        $resepsionis->shift = session("resepsionis_{$id}_shift", 'Pagi'); // Ambil shift dari session
        $resepsionis->nama_hotel = session("resepsionis_{$id}_hotel", 'Hotel ABC'); // Ambil nama hotel dari session

        return view('admin.resepsionis.show', compact('resepsionis')); // Tampilkan view detail resepsionis
    }

    public function edit($id) // Fungsi untuk menampilkan form edit resepsionis
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id); // Cari resepsionis berdasarkan id

        // Ambil nilai dari session biar nyimpen hasil edit terakhir
        $resepsionis->shift = session('shift_' . $resepsionis->id, 'Pagi'); // Ambil shift dari session
        $resepsionis->nama_hotel = session('hotel_' . $resepsionis->id, 'Hotel ABC'); // Ambil nama hotel dari session

        return view('admin.resepsionis.edit', compact('resepsionis')); // Tampilkan view edit resepsionis
    }

    public function update(Request $request, $id) // Fungsi untuk mengupdate data resepsionis
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id); // Cari resepsionis berdasarkan id

        $validated = $request->validate([
            'name' => 'required|string|max:255', // Validasi nama
            'email' => 'required|email|max:255|unique:users,email,' . $resepsionis->id, // Validasi email unik kecuali id ini
            'alamat' => 'nullable|string|max:255', // Validasi alamat (opsional)
            'no_hp' => 'nullable|string|max:15', // Validasi no hp (opsional)
            'shift' => 'nullable|string', // Validasi shift (opsional)
            'nama_hotel' => 'nullable|string', // Validasi nama hotel (opsional)
        ]);

        // Update data utama di DB
        $resepsionis->update($validated); // Update data resepsionis di database

        // Simpan shift & nama hotel ke session
        session(['shift_' . $resepsionis->id => $request->shift]); // Simpan shift ke session
        session(['hotel_' . $resepsionis->id => $request->nama_hotel]); // Simpan nama hotel ke session

        return redirect()->route('admin.resepsionis.index')
                        ->with('success', 'Data resepsionis berhasil diperbarui!'); // Redirect ke halaman index resepsionis dengan pesan sukses
    }

    public function destroy($id) // Fungsi untuk menghapus data resepsionis
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id); // Cari resepsionis berdasarkan id

        // Hapus data user resepsionis dari database
        $resepsionis->delete(); // Hapus data resepsionis dari database

        // Hapus juga data shift & hotel dari session (biar bersih)
        session()->forget("resepsionis_{$id}_shift"); // Hapus shift dari session
        session()->forget("resepsionis_{$id}_hotel"); // Hapus nama hotel dari session

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Data resepsionis berhasil dihapus!'); // Redirect ke halaman index resepsionis dengan pesan sukses
    }
} 
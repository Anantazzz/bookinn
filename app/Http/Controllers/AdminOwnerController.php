<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller
use App\Models\User; // Import model User
use App\Models\Hotel; // Import model Hotel
use Illuminate\Http\Request; // Import Request

class AdminOwnerController extends Controller // Deklarasi class controller
{
    public function index() // Fungsi untuk menampilkan daftar owner
    {
        $owners = User::where('role', 'owner')->with('hotel')->get(); // Ambil data user dengan role owner beserta relasi hotel

        $hotels = Hotel::all(); // Ambil semua data hotel

        return view('admin.owners.index', compact('owners', 'hotels')); // Tampilkan view index owner
    }

    public function store(Request $request) // Fungsi untuk menambah data owner
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Validasi nama
            'email' => 'required|email|unique:users,email', // Validasi email unik
            'password' => 'required|string|min:6', // Validasi password
            'alamat' => 'nullable|string|max:255', // Validasi alamat (opsional)
            'no_hp' => 'nullable|string|max:15', // Validasi no hp (opsional)
            'hotel_id' => 'required|exists:hotels,id', // Validasi hotel_id
        ]);

        User::create([
            'name' => $validated['name'], // Simpan nama
            'email' => $validated['email'], // Simpan email
            'password' => bcrypt($validated['password']), // Simpan password terenkripsi
            'alamat' => $validated['alamat'] ?? null, // Simpan alamat jika ada
            'no_hp' => $validated['no_hp'] ?? null, // Simpan no hp jika ada
            'role' => 'owner', // Set role owner
            'hotel_id' => $validated['hotel_id'], // Simpan hotel_id
        ]);

        return redirect()->route('admin.owners.index')->with('success', 'Owner berhasil ditambahkan!'); // Redirect ke halaman index owner dengan pesan sukses
    }

    public function show($id) // Fungsi untuk menampilkan detail owner
    {
        $owner = User::where('role', 'owner')->with('hotel')->findOrFail($id); // Cari owner beserta relasi hotel
        return view('admin.owners.show', compact('owner')); // Tampilkan view detail owner
    }

    public function edit($id) // Fungsi untuk menampilkan form edit owner
    {
        $owner = User::where('role', 'owner')->findOrFail($id); // Cari owner berdasarkan id
        $hotels = Hotel::all(); // Ambil semua data hotel
        return view('admin.owners.edit', compact('owner', 'hotels')); // Tampilkan view edit owner
    }

    public function update(Request $request, $id) // Fungsi untuk mengupdate data owner
    {
        $owner = User::where('role', 'owner')->findOrFail($id); // Cari owner berdasarkan id

        $validated = $request->validate([
            'name' => 'required|string|max:255', // Validasi nama
            'email' => 'required|email|max:255|unique:users,email,' . $owner->id, // Validasi email unik kecuali id ini
            'alamat' => 'nullable|string|max:255', // Validasi alamat (opsional)
            'no_hp' => 'nullable|string|max:15', // Validasi no hp (opsional)
            'hotel_id' => 'required|exists:hotels,id', // Validasi hotel_id
        ]);

        $owner->update($validated); // Update data owner

        return redirect()->route('admin.owners.index')->with('success', 'Data owner berhasil diperbarui!'); // Redirect ke halaman index owner dengan pesan sukses
    }

    public function destroy($id) // Fungsi untuk menghapus data owner
    {
        $owner = User::where('role', 'owner')->findOrFail($id); // Cari owner berdasarkan id
        $owner->delete(); // Hapus data owner dari database

        return redirect()->route('admin.owners.index')->with('success', 'Owner berhasil dihapus!'); // Redirect ke halaman index owner dengan pesan sukses
    }
} 
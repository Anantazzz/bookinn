<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;

class AdminResepsionisController extends Controller
{
    public function index()
    {
        $resepsionis = User::with('hotel')
            ->where('role', 'resepsionis')
            ->get();

        $hotels = Hotel::all(); 

        return view('admin.resepsionis.index', compact('resepsionis', 'hotels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'hotel_id' => 'required|exists:hotels,id',
            'shift' => 'nullable|in:pagi,malam',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'role' => 'resepsionis',
            'hotel_id' => $validated['hotel_id'], 
            'shift' => $validated['shift'],
        ]);

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Data resepsionis berhasil ditambahkan!');
    }

    public function show($id)
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id);

        // Ambil data non-database dari session
        $resepsionis->shift = session("resepsionis_{$id}_shift", 'Pagi');
        $resepsionis->nama_hotel = session("resepsionis_{$id}_hotel", 'Hotel ABC');

        return view('admin.resepsionis.show', compact('resepsionis'));
    }

    public function edit($id)
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id);

        // Ambil nilai dari session biar nyimpen hasil edit terakhir
        $resepsionis->shift = session('shift_' . $resepsionis->id, 'Pagi');
        $resepsionis->nama_hotel = session('hotel_' . $resepsionis->id, 'Hotel ABC');

        return view('admin.resepsionis.edit', compact('resepsionis'));
    }

    public function update(Request $request, $id)
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $resepsionis->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'shift' => 'nullable|string',
            'nama_hotel' => 'nullable|string',
        ]);

        // Update data utama di DB
        $resepsionis->update($validated);

        // Simpan shift & nama hotel ke session
        session(['shift_' . $resepsionis->id => $request->shift]);
        session(['hotel_' . $resepsionis->id => $request->nama_hotel]);

        return redirect()->route('admin.resepsionis.index')
                        ->with('success', 'Data resepsionis berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $resepsionis = User::where('role', 'resepsionis')->findOrFail($id);

        // Hapus data user resepsionis dari database
        $resepsionis->delete();

        // Hapus juga data shift & hotel dari session (biar bersih)
        session()->forget("resepsionis_{$id}_shift");
        session()->forget("resepsionis_{$id}_hotel");

        return redirect()->route('admin.resepsionis.index')
            ->with('success', 'Data resepsionis berhasil dihapus!');
    }
}

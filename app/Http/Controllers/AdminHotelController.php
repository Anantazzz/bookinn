<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminHotelController extends Controller
{
    // 🔸 Dashboard Admin (dipisah dari index biar nggak bentrok)
    public function dashboard()
    {
        $totalHotel = Hotel::count();
        $totalKota = Hotel::select('kota')->distinct()->count();
        return view('admin.dashboard', compact('totalHotel', 'totalKota'));
    }

    // 🔸 Index Hotel
    public function index(Request $request)
    {
        $query = Hotel::query();

        if ($request->has('kota') && $request->kota != '') {
            $query->where('kota', $request->kota);
        }

        $hotels = $query->get();
        $kotas = Hotel::select('kota')->distinct()->pluck('kota');

        return view('admin.hotels.index', compact('hotels', 'kotas'));
    }

    // 🔸 Show Detail Hotel
    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.show', compact('hotel'));
    }

    // 🔸 Store (Tambah Data Hotel)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kota' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'bintang' => 'required|integer|min:1|max:5',
            'norek' => 'required|string|max:30',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $validated['gambar'] = $filename;
        }

        Hotel::create($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel berhasil ditambahkan.');
    }

    // 🔸 Edit Hotel
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.edit', compact('hotel'));
    }

    // 🔸 Update Hotel
    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'bintang' => 'required|integer|min:1|max:5',
            'norek' => 'required|string|max:30',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // hapus file lama kalau ada
            if ($hotel->gambar && file_exists(public_path('images/' . $hotel->gambar))) {
                unlink(public_path('images/' . $hotel->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $validated['gambar'] = $filename;
        }

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Data hotel berhasil diperbarui.');
    }

    // 🔸 Hapus Hotel
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);

        if ($hotel->gambar && file_exists(public_path('images/' . $hotel->gambar))) {
            unlink(public_path('images/' . $hotel->gambar));
        }

        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'Data hotel berhasil dihapus.');
    }
}

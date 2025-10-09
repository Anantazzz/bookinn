<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminHotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $imageFiles = collect(File::files(public_path('images')))
            ->map(fn($file) => $file->getFilename());

        return view('admin.hotels.create', compact('imageFiles'));
    }

    public function show($id)
    {
    $hotel = Hotel::findOrFail($id);
    return view('admin.hotels.show', compact('hotel'));
    }

   public function store(Request $request)
    {
    $validated = $request->validate([
        'nama_hotel' => 'required|string|max:255',
        'gambar' => 'required|string', // bukan file lagi, tapi path string
        'kota' => 'required|string|max:255',
        'alamat' => 'nullable|string',
        'bintang' => 'required|integer|min:1|max:5',
    ]);

    Hotel::create($validated);

    return redirect()->route('admin.hotels.index')->with('success', 'Hotel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'bintang' => 'required|integer|min:1|max:5',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('hotel_images', 'public');
            $validated['gambar'] = $path;
        }

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Data hotel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'Data hotel berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Hotel;
use App\Models\TipeKamar;
use Illuminate\Http\Request;

class AdminKamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with(['hotel', 'tipeKamar'])->get();
        return view('admin.kamars.index', compact('kamars'));
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'hotel_id' => 'required|exists:hotels,id',
        'nomor_kamar' => 'required|string|max:255|unique:kamars,nomor_kamar',
        'harga' => 'required|numeric|min:0',
        'kapasitas' => 'required|integer|min:1',
        'jumlah_bed' => 'required|integer|min:1',
        'internet' => 'required|boolean',
        'status' => 'required|in:tersedia,booking',
        'tipe_kamar_id' => 'required|exists:tipe_kamars,id',
        'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:102400',
    ]);

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $filename = time() . '_' . $file->getClientOriginalName();

        // pindahkan ke public/images/kamars
        $file->move(public_path('images/kamars'), $filename);

        // simpan nama file-nya aja
        $validated['gambar'] = $filename;
    }

    Kamar::create($validated);

    return redirect()->route('admin.kamars.index')->with('success', 'Kamar berhasil ditambahkan!');
    }
    
    public function show($id)
    {
        $kamar = Kamar::with(['hotel', 'tipeKamar'])->findOrFail($id);

        return view('admin.kamars.show', compact('kamar'));
    }


    public function edit($id)
    {
        $kamar = Kamar::findOrFail($id);
        $hotels = Hotel::all();
        $tipeKamars = TipeKamar::all();

        $imageDir = public_path('images/kamars');
        $imageFiles = is_dir($imageDir) ? array_diff(scandir($imageDir), ['.', '..']) : [];

        return view('admin.kamars.edit', compact('kamar', 'hotels', 'tipeKamars', 'imageFiles'));
    }

    public function update(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'tipe_kamar_id' => 'required|exists:tipe_kamars,id',
            'nomor_kamar' => 'required|string|unique:kamars,nomor_kamar,' . $id,
            'harga' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'jumlah_bed' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,booking',
            'internet' => 'boolean',
            'gambar' => 'nullable|string'
        ]);

        // simpan path gambar dengan folder-nya
        if (!empty($validated['gambar'])) {
            $validated['gambar'] = 'images/kamars/' . $validated['gambar'];
        }

        $kamar->update($validated);

        return redirect()->route('admin.kamars.index')->with('success', 'Data kamar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        return redirect()->route('admin.kamars.index')->with('success', 'Data kamar berhasil dihapus!');
    }
}


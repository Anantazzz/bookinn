<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminResepsionisController extends Controller
{
   public function index()
    {
        $resepsionis = User::where('role', 'resepsionis')->get()->map(function($user) {
            // Ambil shift & nama_hotel dari session (kalau ada)
            $user->shift = session('shift_' . $user->id, 'Pagi');
            $user->nama_hotel = session('hotel_' . $user->id, 'Hotel ABC');
            return $user;
        });

        return view('admin.resepsionis.index', compact('resepsionis'));
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
}

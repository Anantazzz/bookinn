<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Tampilkan halaman profile
    public function index()
    {
        $user = Auth::user(); // ambil data user yang login
        return view('profile.index', compact('user'));
    }

    // Proses update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ]);

       /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update($request->only('name','alamat','no_hp'));


        return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui!');
    }
}

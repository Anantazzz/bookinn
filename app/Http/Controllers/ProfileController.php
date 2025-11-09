<?php // Tag pembuka PHP

namespace App\Http\Controllers; // Namespace controller

use Illuminate\Http\Request; // Import Request
use Illuminate\Support\Facades\Auth; // Import facade Auth

class ProfileController extends Controller // Deklarasi class ProfileController
{
    // Tampilkan halaman profile
    public function index() // Fungsi untuk menampilkan halaman profil pengguna
    {
        $user = Auth::user(); // ambil data user yang login
        return view('profile.index', compact('user')); // Render view profile.index dengan data user
    }

    // Proses update profile
    public function update(Request $request) // Fungsi untuk memproses pembaruan profil
    {
        $user = Auth::user(); // Ambil user yang sedang login

        $request->validate([ // Validasi input
            'name' => 'required|string|max:255', // Nama wajib
            'alamat' => 'nullable|string|max:255', // Alamat opsional
            'no_hp' => 'nullable|string|max:15', // Nomor HP opsional
        ]);

       /** @var \App\Models\User $user */ // Hint tipe untuk IDE
        $user = Auth::user(); // Ambil ulang instance user
        $user->update($request->only('name','alamat','no_hp')); // Update field name, alamat, no_hp


        return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui!'); // Redirect ke route profile dengan pesan sukses
    }
} 
<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller 
{
    // Tampilkan form register
    public function showRegister() // Menampilkan halaman register
    {
        return view('auth.register'); // Render view auth.register
    }

    // Proses register
    public function register(Request $request) // Menangani proses pendaftaran user
    {
        $request->validate([ // Validasi input request
            'name' => 'required', // Nama wajib diisi
            'email' => 'required|email|unique:users', // Email wajib, format email, unik di tabel users
            'password' => 'required|min:6|confirmed', // Password wajib, minimal 6, harus ada konfirmasi
            'alamat' => 'nullable|string', // Alamat opsional
            'no_hp' => 'nullable|string|max:15', // Nomor HP opsional
        ]);

        $user =User::create([ // Buat user baru di database
            'name' => $request->name, // Simpan nama dari request
            'email' => $request->email, // Simpan email dari request
            'password' => Hash::make($request->password), // Hash password sebelum disimpan
            'alamat' => $request->alamat, // Simpan alamat jika ada
            'no_hp' => $request->no_hp, // Simpan no_hp jika ada
            'role' => 'user', // Set role sebagai user
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.'); // Redirect ke halaman login dengan pesan sukses
    }

    // Tampilkan form login
    public function showLogin() // Menampilkan halaman login
    {
        return view('auth.login'); // Render view auth.login
    }

    // Proses login
   public function login(Request $request) // Menangani proses autentikasi/login
    {
        $credentials = $request->validate([ // Validasi kredensial
            'email' => 'required|email', // Email wajib dan harus valid
            'password' => 'required' // Password wajib
        ]);

        if (Auth::attempt($credentials)) { // Coba authenticate dengan kredensial
            $request->session()->regenerate(); // Regenerasi session setelah login

            $user = Auth::user(); // Ambil user yang terautentikasi

            // Cek role dan arahkan sesuai peran
            switch ($user->role) { // Pengalihan berdasarkan role user
                case 'admin':
                    return redirect()->route('admin.dashboard'); // Jika admin => dashboard admin
                case 'resepsionis':
                    return redirect()->route('resepsionis.dashboard'); // Jika resepsionis => dashboard resepsionis
                case 'owner':
                    return redirect()->route('owner.dashboard'); // Jika owner => dashboard owner
                default:
                    return redirect()->route('home'); // Default arahkan ke home
            }
        }

        return back()->withErrors([ // Jika autentikasi gagal, kembali dengan error
            'email' => 'Email atau password salah.', // Pesan error
        ]);
    }
    // Logout
    public function logout(Request $request) // Menangani proses logout
    {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerasi CSRF token

        return redirect()->route('home'); // Redirect ke halaman home
    }
} 
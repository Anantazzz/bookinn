<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use Illuminate\Http\Request;

class ResepKamarController extends Controller
{
    public function index(Request $request)
    {
        $this->updateKamarStatus();
        
        $user = Auth::user();
        $query = Kamar::with(['tipeKamar', 'hotel']);
        
        // Filter kamar berdasarkan hotel resepsionis
        if ($user->hotel_id) {
            $query->where('hotel_id', $user->hotel_id);
        }

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('id', 'asc')->get();

        return view('resepsionis.kamars.index', compact('kamars'));
    }
    
    private function updateKamarStatus()
    {
        $today = now()->toDateString(); // Tanggal hari ini
        
        // 1. RESERVASI YANG SUDAH LEWAT CHECKOUT → SELESAI
        $reservasiSelesai = \App\Models\Reservasi::where('tanggal_checkout', '<', $today)
            ->where('status', 'aktif')
            ->get();
            
        foreach ($reservasiSelesai as $reservasi) {
            $reservasi->update(['status' => 'selesai']); // Reservasi selesai
            $reservasi->kamar->update(['status' => 'tersedia']); // Kamar tersedia lagi
        }
        
        // 2. RESERVASI PENDING YANG SUDAH TANGGAL CHECKIN → AKTIF & KAMAR BOOKING
        $reservasiCheckin = \App\Models\Reservasi::where('tanggal_checkin', '<=', $today)
            ->where('status', 'pending')
            ->get();
            
        foreach ($reservasiCheckin as $reservasi) {
            $reservasi->update(['status' => 'aktif']); // Reservasi aktif (bisa checkin)
            $reservasi->kamar->update(['status' => 'booking']); // Kamar jadi booking
        }
        
        // 3. RESERVASI PENDING YANG BELUM TANGGAL CHECKIN → KAMAR TETAP TERSEDIA
        // KECUALI jika ada reservasi aktif lain di kamar yang sama
        $reservasiBelumCheckin = \App\Models\Reservasi::where('tanggal_checkin', '>', $today)
            ->where('status', 'pending')
            ->get();
            
        foreach ($reservasiBelumCheckin as $reservasi) {
            // CEK APAKAH ADA RESERVASI AKTIF LAIN DI KAMAR YANG SAMA
            $adaReservasiAktif = \App\Models\Reservasi::where('kamar_id', $reservasi->kamar_id)
                ->where('status', 'aktif')
                ->where('id', '!=', $reservasi->id)
                ->exists();
                
            // HANYA UBAH KE TERSEDIA JIKA TIDAK ADA RESERVASI AKTIF LAIN
            if (!$adaReservasiAktif) {
                $reservasi->kamar->update(['status' => 'tersedia']);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);
        $request->validate([
            'status' => 'required|in:tersedia,booking',
        ]);
        $kamar->status = $request->status;
        $kamar->save();

        return redirect()->back()->with('success', 'Status kamar berhasil diubah!');
    }
}





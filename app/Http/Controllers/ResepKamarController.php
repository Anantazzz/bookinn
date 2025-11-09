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
        
        $query = Kamar::with('tipeKamar')->where('hotel_id', Auth::user()->hotel_id);

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('id', 'asc')->get();

        return view('resepsionis.kamars.index', compact('kamars'));
    }
    
    private function updateKamarStatus()
    {
        $reservasiSelesai = \App\Models\Reservasi::where('tanggal_checkout', '<', now()->toDateString())
            ->where('status', 'aktif')
            ->get();
            
        foreach ($reservasiSelesai as $reservasi) {
            $reservasi->update(['status' => 'selesai']);
            $reservasi->kamar->update(['status' => 'tersedia']);
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





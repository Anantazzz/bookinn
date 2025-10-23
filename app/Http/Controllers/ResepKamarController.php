<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use Illuminate\Http\Request;

class ResepKamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Kamar::with('tipeKamar');

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('id', 'asc')->get();

        return view('resepsionis.kamars.index', compact('kamars'));
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

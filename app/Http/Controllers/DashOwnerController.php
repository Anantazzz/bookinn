<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class DashOwnerController extends Controller
{
    public function index()
    {
        $owner = Auth::user();

        // ambil ID hotel milik owner
        $hotelId = $owner->hotel_id;

        // Ambil semua pembayaran dengan status lunas, hanya untuk hotel milik owner
        $pembayarans = Pembayaran::where('status_bayar', 'lunas')
            ->whereHas('reservasi.kamar.hotel', function ($q) use ($hotelId) {
                $q->where('id', $hotelId);
            })
            ->get();

        // Hitung total pemasukan
        $totalPemasukan = $pembayarans->sum('jumlah_bayar');

        // Hitung total transaksi (pembayaran lunas)
        $totalTransaksi = $pembayarans->count();

        // Kelompokkan berdasarkan bulan
        $pemasukanBulanan = $pembayarans->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal_bayar)->format('F Y');
        })->map(function ($group) {
            return $group->sum('jumlah_bayar');
        });

        return view('owner.dashboard', compact('totalPemasukan', 'totalTransaksi', 'pemasukanBulanan'));
    }
}

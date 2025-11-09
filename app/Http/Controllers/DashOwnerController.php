<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class DashOwnerController extends Controller
{
    public function index(Request $request)
    {
        $owner = Auth::user();
        $hotelId = $owner->hotel_id;
        
        if (!$hotelId) {
            return redirect()->route('login')->with('error', 'Akun owner belum dikaitkan dengan hotel.');
        }
        
        $hotel = Hotel::find($hotelId);
        
        if (!$hotel) {
            return redirect()->route('login')->with('error', 'Hotel tidak ditemukan.');
        }

        $startDate = $request->input('start_date', now()->subMonths(5)->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $year = $request->input('year');
        $month = $request->input('month');
        
        if ($year && $month) {
            $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        } elseif ($year) {
            $startDate = \Carbon\Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
            $endDate = \Carbon\Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
        }

        $pembayarans = Pembayaran::where('status_bayar', 'lunas')
            ->whereHas('reservasi.kamar.hotel', function ($q) use ($hotelId) {
                $q->where('id', $hotelId);
            })
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->get();

        $totalPemasukan = $pembayarans->sum('jumlah_bayar');
        $totalTransaksi = $pembayarans->count();

        $pemasukanBulanan = $pembayarans->groupBy(function($payment) {
            return \Carbon\Carbon::parse($payment->tanggal_bayar)->format('F Y');
        })->map(function($group) {
            return $group->sum('jumlah_bayar');
        })->sortKeys();

        return view('owner.dashboard', compact('hotel', 'totalPemasukan', 'totalTransaksi', 'pemasukanBulanan', 'startDate', 'endDate', 'year', 'month'));
    }
} 
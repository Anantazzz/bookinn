<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class DashOwnerController extends Controller
{
    // ==========================================
    // DASHBOARD OWNER - LAPORAN PEMASUKAN HOTEL
    // ==========================================
    public function index(Request $request)
    {
        // VALIDASI OWNER DAN HOTEL
        $owner = Auth::user(); // Ambil data owner yang login
        $hotelId = $owner->hotel_id; // Ambil ID hotel milik owner
        
        if (!$hotelId) { // Jika owner belum dikaitkan dengan hotel
            return redirect()->route('login')->with('error', 'Akun owner belum dikaitkan dengan hotel.'); // Redirect ke login dengan error
        }
        
        $hotel = Hotel::find($hotelId); // Cari data hotel berdasarkan ID
        
        if (!$hotel) { // Jika hotel tidak ditemukan
            return redirect()->route('login')->with('error', 'Hotel tidak ditemukan.'); // Redirect ke login dengan error
        }

        // PENGATURAN FILTER TANGGAL
        $startDate = $request->input('start_date', now()->subMonths(5)->startOfMonth()->toDateString()); // Default: 5 bulan lalu
        $endDate = $request->input('end_date', now()->toDateString()); // Default: hari ini
        $year = $request->input('year'); // Filter berdasarkan tahun
        $month = $request->input('month'); // Filter berdasarkan bulan
        
        if ($year && $month) { // Jika filter tahun dan bulan dipilih
            $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString(); // Awal bulan
            $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString(); // Akhir bulan
        } elseif ($year) { // Jika hanya filter tahun dipilih
            $startDate = \Carbon\Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString(); // 1 Januari
            $endDate = \Carbon\Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString(); // 31 Desember
        }

        // AMBIL DATA PEMBAYARAN LUNAS HOTEL INI
        $pembayarans = Pembayaran::where('status_bayar', 'lunas') // Hanya pembayaran yang sudah lunas
            ->whereHas('reservasi.kamar.hotel', function ($q) use ($hotelId) { // Filter berdasarkan hotel owner
                $q->where('id', $hotelId); // Hotel milik owner yang login
            })
            ->whereBetween('tanggal_bayar', [$startDate, $endDate]) // Filter berdasarkan rentang tanggal
            ->get(); // Ambil semua data

        // HITUNG STATISTIK PEMASUKAN
        $totalPemasukan = $pembayarans->sum('jumlah_bayar'); // Total pemasukan dalam periode
        $totalTransaksi = $pembayarans->count(); // Total jumlah transaksi

        // BUAT ARRAY SEMUA BULAN DALAM PERIODE
        $allMonths = []; // Array untuk menyimpan semua bulan
        $currentDate = \Carbon\Carbon::parse($startDate)->startOfMonth(); // Mulai dari awal bulan start date
        $endDateCarbon = \Carbon\Carbon::parse($endDate)->endOfMonth(); // Sampai akhir bulan end date
        
        while ($currentDate->lte($endDateCarbon)) { // Loop sampai end date
            $monthKey = $currentDate->format('F Y'); // Format: January 2024
            $allMonths[$monthKey] = 0; // Set default pemasukan = 0
            $currentDate->addMonth(); // Tambah 1 bulan
        }
        
        // KELOMPOKKAN PEMASUKAN PER BULAN DARI DATA AKTUAL
        $pemasukanAktual = $pembayarans->groupBy(function($payment) { // Kelompokkan berdasarkan bulan-tahun
            return \Carbon\Carbon::parse($payment->tanggal_bayar)->format('F Y'); // Format: January 2024
        })->map(function($group) { // Hitung total per grup
            return $group->sum('jumlah_bayar'); // Sum pemasukan per bulan
        });
        
        // GABUNGKAN DENGAN SEMUA BULAN (OVERRIDE YANG ADA DATA)
        $pemasukanBulanan = collect($allMonths)->merge($pemasukanAktual); // Merge data aktual ke semua bulan

        // KIRIM DATA KE VIEW DASHBOARD OWNER
        return view('owner.dashboard', compact('hotel', 'totalPemasukan', 'totalTransaksi', 'pemasukanBulanan', 'startDate', 'endDate', 'year', 'month')); // Tampilkan dashboard dengan semua data
    }
} 
@extends('layouts.owner')

@section('title', 'Dashboard Owner')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-10">
        Dashboard Owner — {{ $hotel->nama_hotel }}
    </h1>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-4xl mx-auto">
        <!-- Total Pemasukan -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-0 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-emerald-100 font-semibold text-sm tracking-wide uppercase">Total Pemasukan</h3>
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-white mb-1 tracking-tight">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </p>
                <p class="text-emerald-100 text-sm">Pendapatan keseluruhan</p>
            </div>
        </div>

        <!-- Total Transaksi Lunas -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-0 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-blue-100 font-semibold text-sm tracking-wide uppercase">Total Transaksi Lunas</h3>
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-white mb-1 tracking-tight">{{ $totalTransaksi }}</p>
                <p class="text-blue-100 text-sm">Transaksi berhasil</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-5 border-b">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Pemasukan per Bulan</h3>
                    <p class="text-gray-300 text-sm mt-0.5">Rincian pemasukan bulanan</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <th class="p-4 border-b-2 border-gray-200 font-bold text-gray-700 text-sm uppercase tracking-wider">Bulan</th>
                        <th class="p-4 border-b-2 border-gray-200 font-bold text-gray-700 text-sm uppercase tracking-wider text-right">Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse($pemasukanBulanan as $bulan => $total)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 border-b border-gray-100 group">
                            <td class="p-4 font-semibold text-gray-800 group-hover:text-gray-900">
                                <div class="flex items-center gap-2">
                                    <div class="w-1 h-8 bg-gradient-to-b from-emerald-500 to-emerald-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    {{ $bulan }}
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <span class="font-bold text-gray-900 text-lg group-hover:text-emerald-600 transition-colors">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-gray-100 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 font-semibold text-lg mb-1">Belum ada data pemasukan</p>
                                    <p class="text-gray-400 text-sm">Data akan muncul setelah ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
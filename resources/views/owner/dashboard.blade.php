@extends('layouts.owner')

@section('title', 'Dashboard Owner')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Header --}}
            <div class="mb-8 text-center">
                <div class="inline-flex items-center gap-3 mb-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dashboard Owner</p>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $hotel->nama_hotel }}</h1>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-5xl mx-auto">
                <!-- Total Pemasukan -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500 to-emerald-600 opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
                                Total
                            </span>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2 uppercase tracking-wide">Total Pemasukan</h3>
                        <p class="text-3xl font-bold text-gray-900 mb-2">
                            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500">Pendapatan keseluruhan</p>
                    </div>
                </div>

                <!-- Total Transaksi Lunas -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                Lunas
                            </span>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2 uppercase tracking-wide">Total Transaksi Lunas</h3>
                        <p class="text-3xl font-bold text-gray-900 mb-2">{{ $totalTransaksi }}</p>
                        <p class="text-sm text-gray-500">Transaksi berhasil</p>
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden max-w-5xl mx-auto">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Pemasukan per Bulan</h3>
                                <p class="text-gray-500 text-sm mt-0.5">5 bulan terakhir</p>
                            </div>
                        </div>
                        @if(count($pemasukanBulanan) > 5)
                            <a href="{{ route('owner.laporan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all duration-200">
                                Lihat Semua
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                <th class="py-4 px-6 text-sm font-bold text-gray-700 uppercase tracking-wider">Bulan</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700 uppercase tracking-wider text-right">Total Pemasukan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $previewData = count($pemasukanBulanan) > 5 
                                    ? array_slice($pemasukanBulanan->toArray(), -5, 5, true)
                                    : $pemasukanBulanan;
                            @endphp
                            @forelse($previewData as $bulan => $total)
                                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-1 h-8 bg-gradient-to-b from-emerald-500 to-emerald-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <span class="font-semibold text-gray-800 group-hover:text-gray-900">{{ $bulan }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="font-bold text-gray-900 text-lg group-hover:text-emerald-600 transition-colors">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-12 text-center">
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

                @if(count($pemasukanBulanan) > 5)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center">
                        <a href="{{ route('owner.laporan') }}" class="inline-flex items-center gap-2 text-gray-700 hover:text-emerald-600 font-medium text-sm transition-colors group">
                            Lihat {{ count($pemasukanBulanan) - 5 }} bulan lainnya
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
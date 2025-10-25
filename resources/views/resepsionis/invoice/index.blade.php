@extends('layouts.resep')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Kelola & Cetak Invoice</h1>
                        <p class="text-gray-600 text-sm mt-1">Manajemen tagihan pelanggan</p>
                    </div>
                </div>
            </div>

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Search Bar --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 mb-6">
                <form method="GET" action="{{ route('resepsionis.invoice.index') }}" class="flex gap-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                            name="search" 
                            placeholder="Cari nama pelanggan atau kode invoice..." 
                            value="{{ request('search') }}" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-200">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">#</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Kamar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Tipe Kamar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Tanggal Bayar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Jumlah Bayar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Kode Invoice</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pembayarans as $index => $p)
                                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                                    <td class="py-4 px-6">
                                        <span class="font-semibold text-gray-600">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                                {{ substr($p->reservasi->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $p->reservasi->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-lg font-medium text-gray-800">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            {{ $p->reservasi->kamar->nomor_kamar }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1.5 bg-gradient-to-r from-purple-100 to-purple-50 border border-purple-200 rounded-lg text-sm font-medium text-purple-800">
                                            {{ $p->reservasi->kamar->tipeKamar->nama_tipe ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-sm font-semibold text-gray-800">{{ $p->tanggal_bayar }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-gray-900 text-base">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($p->status_bayar === 'pending')
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-orange-100 to-orange-50 border border-orange-300 rounded-lg text-orange-800 font-semibold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-emerald-100 to-emerald-50 border border-emerald-300 rounded-lg text-emerald-800 font-semibold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Success
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $invoice = $p->invoice()->latest()->first();
                                        @endphp
                                        @if($invoice)
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-blue-100 to-blue-50 border border-blue-200 rounded-lg text-blue-800 font-mono font-semibold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                </svg>
                                                {{ $invoice->kode_unik }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm font-medium">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($p->status_bayar === 'pending')
                                            <form action="{{ route('resepsionis.invoice.accept', $p->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md hover:shadow-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Accept
                                                </button>
                                            </form>
                                        @else
                                            @if($invoice)
                                                <a href="{{ route('resepsionis.invoice.print', $invoice->id) }}"
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-lg hover:from-amber-600 hover:to-amber-700 shadow-md hover:shadow-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                    Cetak Invoice
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 font-semibold text-lg mb-1">Tidak ada data pembayaran</p>
                                            <p class="text-gray-400 text-sm">Data akan muncul setelah ada transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
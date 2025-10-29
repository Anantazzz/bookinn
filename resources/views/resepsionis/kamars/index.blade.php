@extends('layouts.resep')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Ketersediaan Kamar</h1>
                    </div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 mb-6">
                <form method="GET" action="{{ route('resepsionis.kamars.index') }}" class="flex gap-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                            name="search" 
                            placeholder="Cari nomor kamar..." 
                            value="{{ request('search') }}" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md hover:shadow-lg transition-all duration-200">
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
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Nomor Kamar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Tipe Kamar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Harga</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Kapasitas</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Internet</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kamars as $index => $kamar)
                                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                                    <td class="py-4 px-6">
                                        <span class="font-semibold text-gray-600">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                                {{ $kamar->nomor_kamar }}
                                            </div>
                                            <span class="font-semibold text-gray-800">Kamar {{ $kamar->nomor_kamar }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1.5 bg-gradient-to-r from-purple-100 to-purple-50 border border-purple-200 rounded-lg text-sm font-medium text-purple-800">
                                            {{ $kamar->tipeKamar->nama_tipe ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-gray-900 text-base">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-800">{{ $kamar->kapasitas }} orang</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($kamar->internet)
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-blue-100 to-blue-50 border border-blue-200 rounded-lg text-blue-800 font-semibold text-sm">
                                                Ya
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-gray-100 to-gray-50 border border-gray-200 rounded-lg text-gray-600 font-semibold text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('resepsionis.kamars.update', $kamar->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" 
                                                    onchange="this.form.submit();"
                                                    class="px-4 py-2 rounded-lg font-semibold text-sm text-white cursor-pointer transition-all duration-200 shadow-md hover:shadow-lg focus:ring-2 focus:ring-offset-2 {{ $kamar->status == 'tersedia' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:ring-emerald-500' : 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-red-500' }}">
                                                <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>✓ Tersedia</option>
                                                <option value="booking" {{ $kamar->status == 'booking' ? 'selected' : '' }}>✗ Booking</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 font-semibold text-lg mb-1">Belum ada data kamar</p>
                                            <p class="text-gray-400 text-sm">Tambahkan kamar untuk mulai mengelola ketersediaan</p>
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
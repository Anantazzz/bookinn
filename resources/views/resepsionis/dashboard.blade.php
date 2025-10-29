@extends('layouts.resep')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Judul --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dashboard Resepsionis</p>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $hotel->nama_hotel }}</h1>
                </div>
            </div>
        </div>
        
        {{-- Grid Card Menu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Ketersediaan Kamar --}}
            <a href="{{ route('resepsionis.kamars.index') }}"
            class="group relative bg-white shadow-xl rounded-3xl p-8 text-center overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-amber-100">
                {{-- Background Decoration --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-200 to-amber-300 rounded-full opacity-20 -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fa-solid fa-bed text-3xl text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Ketersediaan Kamar</h2>
                    <p class="text-gray-600 text-sm">Cek status kamar hotel</p>
                </div>
                
                {{-- Hover Arrow --}}
                <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </a>
            
            {{-- Check-in / Check-out --}}
            <a href="{{ route('resepsionis.check.index') }}" 
            class="group relative bg-white shadow-xl rounded-3xl p-8 text-center overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-emerald-100">
                {{-- Background Decoration --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-200 to-emerald-300 rounded-full opacity-20 -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-2xl mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fa-solid fa-door-open text-3xl text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Check-in / Check-out</h2>
                    <p class="text-gray-600 text-sm">Konfirmasi kedatangan & kepulangan tamu</p>
                </div>
                
                {{-- Hover Arrow --}}
                <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </a>

            {{-- Cetak Invoice --}}
            <a href="{{ route('resepsionis.invoice.index') }}" 
            class="group relative bg-white shadow-xl rounded-3xl p-8 text-center overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-indigo-100">
                {{-- Background Decoration --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-200 to-indigo-300 rounded-full opacity-20 -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-2xl mb-5 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fa-solid fa-file-invoice-dollar text-3xl text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Cetak Invoice</h2>
                    <p class="text-gray-600 text-sm">Kelola dan cetak tagihan pelanggan</p>
                </div>
                
                {{-- Hover Arrow --}}
                <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.resep')

@section('content')

<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-6">
        {{-- Judul --}}
       <h1 class="text-3xl font-bold text-gray-800 text-center mb-10">
            Dashboard Resepsionis — {{ $hotel->nama_hotel }}
        </h1>
        
        {{-- Grid Card Menu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Ketersediaan Kamar --}}
            <a href="{{ route('resepsionis.kamars.index') }}"
            class="bg-amber-300 shadow-md rounded-2xl p-6 text-center text-gray-800 hover:bg-amber-400 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="mb-3">
                    <i class="fa-solid fa-bed text-4xl"></i>
                </div>
                <h2 class="text-lg font-semibold">Ketersediaan Kamar</h2>
                <p class="text-gray-700 text-sm mt-2">Cek status kamar hotel</p>
            </a>
            
            {{-- Check-in / Check-out --}}
            <a href="{{ route('resepsionis.check.index') }}" 
            class="bg-emerald-400 shadow-md rounded-2xl p-6 text-center text-white hover:bg-emerald-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="mb-3">
                    <i class="fa-solid fa-door-open text-4xl"></i>
                </div>
                <h2 class="text-lg font-semibold">Check-in / Check-out</h2>
                <p class="text-emerald-100 text-sm mt-2">Konfirmasi kedatangan & kepulangan tamu</p>
            </a>

            {{-- Cetak Invoice --}}
            <a href="{{ route('resepsionis.invoice.index') }}" 
            class="bg-indigo-400 shadow-md rounded-2xl p-6 text-center text-white hover:bg-indigo-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="mb-3">
                    <i class="fa-solid fa-file-invoice-dollar text-4xl"></i>
                </div>
                <h2 class="text-lg font-semibold">Cetak Invoice</h2>
                <p class="text-indigo-100 text-sm mt-2">Kelola dan cetak tagihan pelanggan</p>
            </a>
        </div>
    </div>
</div>
@endsection

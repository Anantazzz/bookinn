@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
<section class="position-relative rounded-4 overflow-hidden mb-5 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    {{-- Background Image with Overlay --}}
    <div class="position-relative" style="height: 500px;">
        <img src="{{ asset('images/hero_hotel.jpg') }}" 
             alt="Hero Background" 
             class="w-100 h-100" 
             style="object-fit: cover; opacity: 0.9;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 100%);"></div>
    </div>

    {{-- Hero Content --}}
    <div class="position-absolute top-50 start-50 translate-middle w-100 text-center px-4">
        <div class="mb-4">
        </div>
        
        {{-- Search Bar Component --}}
        <div class="mx-auto" style="max-width: 700px;">
            <form action="{{ route('hotel') }}" method="GET">
                <div class="bg-white rounded-4 shadow-lg p-2" style="backdrop-filter: blur(10px);">
                    <x-search-bar />
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Rekomendasi Hotel --}}
<section class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Rekomendasi Hotel</h3>
            <p class="text-muted mb-0">Pilihan terbaik untuk liburan Anda</p>
        </div>
        <a href="{{ route('hotel') }}" class="btn btn-outline-primary rounded-pill px-4">
            Lihat Semua
            <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>

    <div class="position-relative">
        {{-- Wrapper Card --}}
        <div class="d-flex flex-nowrap overflow-auto pb-3 gap-3" style="scroll-behavior: smooth; scrollbar-width: thin;">
            @foreach($hotels as $hotel)
                <a href="{{ route('hotel.detail', $hotel->id) }}" class="text-decoration-none">
                    <div class="card hotel-card border-0 shadow-sm rounded-4 overflow-hidden" style="min-width: 280px; transition: all 0.3s ease;">
                        {{-- Gambar dengan Badge --}}
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            <img src="{{ asset('images/'.$hotel->gambar) }}" 
                                 alt="{{ $hotel->nama_hotel }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-star-fill"></i> {{ $hotel->bintang }}
                                </span>
                            </div>
                        </div>

                        {{-- Info Hotel --}}
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2 text-dark">{{ $hotel->nama_hotel }}</h5>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                <span class="fw-semibold">{{ $hotel->kota }}</span>
                            </div>
                            <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $hotel->alamat }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                                <span class="text-primary fw-bold">Lihat Detail</span>
                                <i class="bi bi-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Pilih Kota --}}
<section class="mb-5">
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Destinasi Populer</h3>
        <p class="text-muted mb-0">Jelajahi kota-kota wisata favorit di Indonesia</p>
    </div>

    @php
        $cities = [
            ['name' => 'Jakarta', 'image' => 'jkt.jpg'],
            ['name' => 'Bali', 'image' => 'bali.jpg'],
            ['name' => 'Bandung', 'image' => 'bandung.jpg'],
            ['name' => 'Lombok', 'image' => 'lombok.jpg'],
            ['name' => 'Yogyakarta', 'image' => 'yogya.jpeg'],
            ['name' => 'Pangandaran', 'image' => 'pangandaran.jpg'],
            ['name' => 'Malang', 'image' => 'malang.jpeg'],
            ['name' => 'Palembang', 'image' => 'palembang.jpeg'],
            ['name' => 'Surabaya', 'image' => 'surabaya.jpeg'],
            ['name' => 'Papua', 'image' => 'papua.jpg'],
            ['name' => 'Sukabumi', 'image' => 'skbm.jpeg'],
            ['name' => 'Maluku', 'image' => 'maluku.jpg'],
        ];
    @endphp

    <div class="row g-4">
        @foreach ($cities as $city)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('hotel', ['kota' => $city['name']]) }}" class="text-decoration-none">
                    <div class="city-card position-relative border-0 rounded-4 overflow-hidden shadow-sm" style="height: 220px; cursor: pointer;">
                        <img src="{{ asset('images/'.$city['image']) }}" 
                            alt="{{ $city['name'] }}" 
                            class="w-100 h-100" 
                            style="object-fit: cover; transition: transform 0.4s ease;">
                        
                        {{-- Gradient Overlay --}}
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);"></div>
                        
                        {{-- City Name --}}
                        <div class="position-absolute bottom-0 start-0 p-4 w-100">
                            <h5 class="text-white fw-bold mb-1" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                                {{ $city['name'] }}
                            </h5>
                            <div class="d-flex align-items-center text-white opacity-75">
                                <i class="bi bi-buildings me-2"></i>
                                <span class="small">Jelajahi Hotel</span>
                            </div>
                        </div>

                        {{-- Hover Icon --}}
                        <div class="position-absolute top-50 start-50 translate-middle city-icon opacity-0" style="transition: opacity 0.3s ease;">
                            <div class="bg-white rounded-circle p-3 shadow-lg">
                                <i class="bi bi-arrow-right text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

{{-- Custom Styles --}}
<style>
    /* Hotel Card Hover */
    .hotel-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
    }

    .hotel-card:hover img {
        transform: scale(1.05);
    }

    /* City Card Hover */
    .city-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .city-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 16px 32px rgba(0,0,0,0.25) !important;
    }

    .city-card:hover img {
        transform: scale(1.1);
    }

    .city-card:hover .city-icon {
        opacity: 1 !important;
    }

    /* Scrollbar Styling */
    .overflow-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Smooth Animations */
    * {
        transition: all 0.3s ease;
    }

    /* Bootstrap Icons (if not already included) */
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
</style>

@endsection
@extends('layouts.app')

@section('content')

   {{-- Hero Section --}}
<section class="position-relative rounded-4 overflow-hidden mb-5">
    {{-- Background Image --}}
    <img src="{{ asset('images/hero_hotel.jpg') }}" 
         alt="Hero Background" 
         class="w-100" 
         style="height: 420px; object-fit: cover;">

    {{-- Search Bar Component --}}
    <div class="position-absolute top-50 start-50 translate-middle w-75">
        <form action="{{ route('hotel') }}" method="GET">
            <x-search-bar />
        </form>
    </div>
</section>

{{-- Rekomendasi Hotel --}}
<section class="mb-5">
    <h4 class="fw-bold mb-4">Rekomendasi hotel untuk anda</h4>
    <div class="position-relative">
        {{-- Wrapper Card --}}
        <div class="d-flex flex-nowrap overflow-auto pb-3" style="gap: 1rem;">
            @foreach($hotels as $hotel)
             <a href="{{ route('hotel.detail', $hotel->id) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm rounded-3" style="min-width: 220px; overflow:hidden;">
                    {{-- Gambar --}}
                    <div style="height: 140px; overflow:hidden;">
                        <img src="{{ asset('images/'.$hotel->gambar) }}" 
                             alt="{{ $hotel->nama_hotel }}" 
                             class="w-100 h-100" 
                             style="object-fit: cover;">
                    </div>

                    {{-- Info Hotel --}}
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ $hotel->nama_hotel }}</h6>
                        <p class="text-muted mb-1">{{ $hotel->kota }}</p>
                        <p class="text-muted small">{{ $hotel->alamat }}</p>

                        <div class="d-flex align-items-center mt-2">
                            <span class="text-warning fw-bold ms-auto">★ {{ $hotel->bintang }}</span>
                        </div>
                    </div>
                  </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

    {{-- Pilih Kota --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4">Kota Wisata</h4>
        <div class="row g-3">
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

            <div class="row g-3">
            @foreach ($cities as $city)
            <div class="col-md-3">
                <a href="{{ route('hotel', ['kota' => $city['name']]) }}" class="text-decoration-none">
                    <div class="card city-card border-0 shadow-sm rounded-4 overflow-hidden" style="cursor: pointer;">
                        <img src="{{ asset('images/'.$city['image']) }}" 
                            alt="{{ $city['name'] }}" 
                            class="w-100" 
                            style="height: 180px; object-fit: cover; border-radius: 10px;">
                        <div class="card-img-overlay d-flex align-items-end p-2">
                            <h6 class="text-white fw-bold m-0 text-shadow">{{ $city['name'] }}</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        </div>
    </section>

   {{-- Css Kota --}}
    <style>
        .city-card {
            transition: all 0.3s ease;
        }
        .city-card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
    </style>
@endsection

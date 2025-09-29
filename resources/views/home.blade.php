@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="position-relative rounded-4 overflow-hidden mb-5">
        {{-- Background Image --}}
        <img src="{{ asset('images/hero_hotel.jpg') }}" 
             alt="Hero Background" 
             class="w-100" 
             style="height: 420px; object-fit: cover;">

        {{-- Search Box --}}
        <div class="position-absolute top-50 start-50 translate-middle w-75">
            <div class="bg-white shadow rounded-4 p-4 d-flex flex-column flex-lg-row align-items-stretch gap-3">
                
                {{-- Where to --}}
                <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        <span class="fw-semibold">Where to?</span>
                    </div>
                    <input type="text" class="form-control border-0 p-0 shadow-none" 
                           placeholder="Jakarta, Special Capital Region...">
                </div>

                {{-- Dates --}}
                <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span class="fw-semibold">Dates</span>
                    </div>
                    <input type="text" class="form-control border-0 p-0 shadow-none" 
                           placeholder="22 Sept - 26 Sept">
                </div>

                {{-- Travellers --}}
                <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-person-fill me-2"></i>
                        <span class="fw-semibold">Travellers</span>
                    </div>
                    <input type="text" class="form-control border-0 p-0 shadow-none" 
                           placeholder="2 Travellers, 1 Room">
                </div>

                {{-- Button --}}
                <div class="d-flex align-items-center">
                    <button class="btn btn-dark rounded-3 px-4 py-3 fw-semibold">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Rekomendasi Hotel --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4">Rekomendasi hotel untuk anda</h4>
        <div class="position-relative">
            {{-- Wrapper Card --}}
            <div class="d-flex flex-nowrap overflow-auto pb-3" style="gap: 1rem;">
                @foreach($hotels as $hotel)
                    <x-hotel-card 
                        :nama-hotel="$hotel->nama_hotel"
                        :gambar="$hotel->gambar"
                        :kota="$hotel->kota"
                        :alamat="$hotel->alamat"
                        :rating="$hotel->rating"
                        :bintang="$hotel->bintang"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pilih Kota --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4">Pilih Kota tujuanmu</h4>
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

            @foreach($cities as $city)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <img src="{{ asset('images/'.$city['image']) }}" 
                                 alt="{{ $city['name'] }}" 
                                 class="w-100" 
                                 style="height: 150px; object-fit: cover;">
                            <div class="card-img-overlay d-flex align-items-end p-2">
                                <h6 class="text-white fw-bold m-0 text-shadow">{{ $city['name'] }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .text-shadow {
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        }
    </style>
@endsection

@extends('layouts.app')

@section('content')

{{-- Promo Popup --}}
<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 btn-close-white" data-bs-dismiss="modal"></button>
                
                <div class="text-center text-white p-5">
                    <div class="mb-4">
                        <i class="bi bi-gift-fill" style="font-size: 4rem; color: #ffd700;"></i>
                    </div>
                    <h2 class="fw-bold mb-3">🎉 PROMO SPESIAL HARI INI! 🎉</h2>
                    <p class="fs-5 mb-4">Dapatkan diskon hingga <span class="fw-bold text-warning">20%</span> untuk booking hotel impian Anda!</p>
                </div>
                
                <div class="bg-white p-4">
                    <div class="row g-3 text-center">
                        <div class="col-md-4">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                <i class="bi bi-percent text-primary fs-3 mb-2"></i>
                                <h6 class="fw-bold text-primary">WELCOME10</h6>
                                <small class="text-muted">Diskon 10% untuk customer baru</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                <i class="bi bi-calendar-event text-success fs-3 mb-2"></i>
                                <h6 class="fw-bold text-success">WEEKEND20</h6>
                                <small class="text-muted">Diskon 20% untuk booking weekend</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                <i class="bi bi-cash-coin text-warning fs-3 mb-2"></i>
                                <h6 class="fw-bold text-warning">SAVE50K</h6>
                                <small class="text-muted">Potongan langsung Rp 50.000</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('hotel') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="bi bi-search me-2"></i>Cari Hotel Sekarang
                        </a>
                        <p class="small text-muted mt-3 mb-0">
                            <i class="bi bi-clock me-1"></i>Promo terbatas! Jangan sampai terlewat
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        <div class="mx-auto search-container">
            <form action="{{ route('hotel') }}" method="GET">
                <div class="bg-white rounded-4 shadow-lg search-box" style="backdrop-filter: blur(10px);">
                    <x-search-bar />
                    <div class="text-center mt-3">
                        <small class="text-muted bg-white bg-opacity-75 px-3 py-1 rounded-pill">
                            <i class="bi bi-gift text-primary me-1"></i>
                            Ada promo menarik menanti Anda!
                        </small>
                    </div>
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
    
    /* Responsive Search Bar */
    .search-container {
        max-width: 900px;
    }
    
    .search-box {
        padding: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .search-container {
            max-width: 95%;
        }
        
        .search-box {
            padding: 1rem;
        }
    }
    
    /* Promo Modal Animation */
    .modal.fade .modal-dialog {
        transform: scale(0.8) translateY(-50px);
        transition: all 0.3s ease;
    }
    
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
</style>

{{-- Promo Modal Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    @auth
    // Cek apakah popup sudah ditampilkan untuk session login ini
    const sessionKey = 'promoShown_{{ auth()->id() }}_{{ session()->getId() }}';
    const hasShown = sessionStorage.getItem(sessionKey);
    
    if (!hasShown) {
        // Tampilkan popup setelah 2 detik
        setTimeout(function() {
            const promoModal = new bootstrap.Modal(document.getElementById('promoModal'));
            promoModal.show();
            
            // Tandai sudah ditampilkan untuk session ini
            sessionStorage.setItem(sessionKey, 'true');
        }, 2000);
    }
    @else
    // Untuk guest, tampilkan sekali per hari
    const today = new Date().toDateString();
    const lastShown = localStorage.getItem('promoPopupLastShown');
    
    if (lastShown !== today) {
        setTimeout(function() {
            const promoModal = new bootstrap.Modal(document.getElementById('promoModal'));
            promoModal.show();
            localStorage.setItem('promoPopupLastShown', today);
        }, 2000);
    }
    @endauth
});
</script>

@endsection
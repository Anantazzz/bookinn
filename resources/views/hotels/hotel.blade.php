@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row g-4">
        
        {{-- Sidebar Filter --}}
        <div class="col-lg-3 col-md-4">
            <div class="position-sticky" style="top: 20px;">
                @include('partials.sidebar')
            </div>
        </div>

        {{-- Area Hotel List --}}
        <div class="col-lg-9 col-md-8">
            
            {{-- Search Bar --}}
            <div class="mb-4">
                <form action="{{ route('hotel') }}" method="GET">
                    <x-search-bar />
                </form>
            </div>

            {{-- Header --}}
            <div class="mb-4">
                <h4 class="fw-bold" style="color: #1a1a1a;">Hotel Tersedia</h4>
                <p class="text-muted mb-0">Temukan hotel terbaik untuk perjalanan Anda</p>
            </div>

            {{-- List Hotel --}}
            <div class="row g-3">
                @forelse($hotels as $hotel)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden transition-all" 
                             style="transition: transform 0.2s, box-shadow 0.2s;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
                            
                            <div class="row g-0 align-items-center">
                                
                                {{-- Foto Hotel --}}
                                <div class="col-md-3">
                                    <div class="position-relative" style="height: 200px;">
                                        <img src="{{ asset('images/'.$hotel->gambar) }}" 
                                             alt="{{ $hotel->nama_hotel }}" 
                                             class="w-100 h-100" 
                                             style="object-fit: cover;">
                                        
                                        {{-- Badge Bintang di atas gambar --}}
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-white text-warning px-3 py-2 rounded-pill shadow-sm fw-bold" style="font-size: 0.9rem;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="mb-1" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                </svg>
                                                {{ $hotel->bintang }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Info Hotel --}}
                                <div class="col-md-7">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.35rem;">
                                            {{ $hotel->nama_hotel }}
                                        </h5>
                                        
                                        <div class="d-flex flex-column gap-2 mb-3">
                                            <div class="d-flex align-items-center text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 flex-shrink-0" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                                <span style="font-size: 0.95rem;">{{ $hotel->kota }}</span>
                                            </div>

                                            <div class="d-flex align-items-start text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2 flex-shrink-0 mt-1" viewBox="0 0 16 16">
                                                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                                <span style="font-size: 0.9rem; line-height: 1.5;">{{ $hotel->alamat }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Button Lihat --}}
                                <div class="col-md-2">
                                    <div class="p-4 d-flex align-items-center justify-content-center h-100">
                                        <a href="{{ route('hotel.detail', $hotel->id) }}" 
                                           class="btn btn-primary rounded-pill px-4 py-2 fw-semibold w-100"
                                           style="transition: all 0.2s;">
                                            Lihat Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="ms-1 mb-1" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-light">
                            <div class="card-body text-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="text-muted mb-3" viewBox="0 0 16 16">
                                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                </svg>
                                <h5 class="text-muted fw-semibold mb-2">Tidak Ada Hotel Ditemukan</h5>
                                <p class="text-muted mb-0">Coba ubah filter atau kata kunci pencarian Anda</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    @media (max-width: 768px) {
        .col-md-3 img {
            height: 180px !important;
        }
    }
</style>
@endsection
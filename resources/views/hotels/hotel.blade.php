@extends('layouts.app')

@section('content')

{{-- Sidebar Filter --}}
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('partials.sidebar')
            </div>

        {{-- Area Hotel List --}}
        <div class="col-md-9">
            
            {{-- Search Bar --}}
            <div class="mb-4">
                <form action="{{ route('hotel') }}" method="GET">
                    <x-search-bar />
                </form>
            </div>

       {{-- List Hotel --}}
            <div>
                @forelse($hotels as $hotel)
                    <div class="d-flex align-items-stretch bg-white shadow-sm rounded-4 mb-3" style="overflow:hidden;">
                        {{-- Foto Hotel --}}
                        <div style="width: 200px; height: 140px; flex-shrink:0;">
                            <img src="{{ asset('images/'.$hotel->gambar) }}" 
                                alt="{{ $hotel->nama_hotel }}" 
                                class="w-100 h-100" 
                                style="object-fit: cover;">
                        </div>

                        {{-- Info Hotel --}}
                        <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <h5 class="fw-semibold mb-1">{{ $hotel->nama_hotel }}</h5>
                                <p class="text-muted mb-2">{{ $hotel->kota }}</p>
                                <p class="text-muted mb-2">{{ $hotel->alamat }}</p>
                            </div>

                            {{-- Button Lihat --}}
                            <a href="{{ route('hotel.detail', $hotel->id) }}" 
                            class="btn btn-sm btn-primary mt-2 align-self-start">
                                Lihat
                            </a>
                        </div>

                        {{-- Bintang & Rating Hotel --}}
                        <div class="p-3 d-flex flex-column align-items-end justify-content-end h-100">
                            <span class="text-warning fw-bold">★ {{ $hotel->bintang }}</span>
                            <span class="badge bg-success mt-2">{{ $hotel->rating }}</span>
                        </div>
                    </div>
                @empty
                    <div class="border rounded p-5 text-center text-muted">
                        <em>Belum ada hotel yang tersedia</em>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

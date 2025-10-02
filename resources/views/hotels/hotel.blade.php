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
                   <div class="animate__animated animate__zoomIn hotel-card-wrapper mb-3">
                    <x-hotel-card 
                        :id="$hotel->id"
                        :gambar="$hotel->gambar"
                        :namaHotel="$hotel->nama_hotel"
                        :kota="$hotel->kota"
                        :alamat="$hotel->alamat"
                        :rating="$hotel->rating"
                        :bintang="$hotel->bintang"
                    />
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

@extends('layouts.app')

@section('content')

{{-- Header Hotel dengan Gambar --}}
<section class="mb-4">
    <div class="rounded-3 overflow-hidden shadow-sm">
        <img src="{{ asset('images/'.$hotel->gambar) }}" 
             alt="{{ $hotel->nama_hotel }}" 
             class="w-100" 
             style="height: 280px; object-fit: cover;">
    </div>
</section>

{{-- Info Hotel --}}
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="flex-grow-1">
            <h1 class="fw-bold mb-3" style="font-size: 2rem;">{{ $hotel->nama_hotel }}</h1>
            <p class="text-secondary mb-2 fw-semibold" style="font-size: 0.95rem;">
                <i class="bi bi-geo-alt-fill"></i> {{ $hotel->kota }}
            </p>
            <p class="text-secondary mb-0 fw-semibold" style="font-size: 0.9rem;">
                {{ $hotel->alamat }}
            </p>
        </div>
        
        {{-- Rating Badge --}}
        <div class="text-end ms-3">
            <div class="d-flex align-items-center gap-1 mb-2">
                <span class="text-warning" style="font-size: 1.2rem;">★</span>
                <span class="fw-bold">{{ $hotel->bintang }}</span>
            </div>
            <span class="badge rounded-pill px-3 py-2" 
                  style="background-color: #059669; font-size: 0.9rem;">
                {{ number_format($hotel->rating, 1) }}
            </span>
        </div>
    </div>
</section>

{{-- List Kamar Section --}}
<section class="mt-5">
    <h2 class="fw-bold mb-4" style="font-size: 1.5rem;">Pilih kamar Anda</h2>
    
    <div class="row g-4">
             @foreach ($kamarData as $kamar)
            <div class="col-md-4">
                <x-room-card 
                    :id="$kamar['id']"
                    :gambar="$kamar['gambar']"
                    :tipeKamar="$kamar['tipeKamar']"
                    :harga="$kamar['harga']"
                    :kapasitas="$kamar['kapasitas']"
                    :jumlahBed="$kamar['jumlahBed']"
                    :internet="$kamar['internet']"
                    :sisaKamar="$kamar['sisaKamar']"
                    :status="$kamar['sisaKamar'] > 0 ? 'tersedia' : 'habis'"
                />
            </div>
        @endforeach
    </div>
</section>

@endsection

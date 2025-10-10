@props(['id' => null, 'gambar' => null, 'tipeKamar' => '', 'harga' => 0, 'status' => '', 'kapasitas' => 0, 'jumlahBed' => 0, 'internet' => false])

{{-- Header detail --}}
<div class="card shadow-sm rounded-4 mb-3" style="width: 100%;">
   <img src="{{ asset('images/kamars/' . $gambar) }}" 
     class="card-img-top rounded-top-4" 
     alt="{{ $tipeKamar }}" 
     style="height: 160px; object-fit: cover;">

{{-- -Fasilitas Kamar --}}
    <div class="card-body">
      <h6 class="card-title fw-bold mb-3" style="font-size: 1.4rem;">{{ $tipeKamar }}</h6>
      <p class="text-muted mb-2" style="font-size: 0.9rem;">
        <p class="text-muted mb-2" style="font-size: 0.9rem;">
            Kapasitas: {{ $kapasitas }} orang
        </p>
        <p class="text-muted mb-2" style="font-size: 0.9rem;">
            Jumlah Bed: {{ $jumlahBed }}
        </p>
        <p class="text-muted mb-3" style="font-size: 0.9rem;">
            Internet: 
            <span class="{{ $internet ? 'text-success' : 'text-danger' }}">
                {{ $internet ? 'Tersedia' : 'Tidak Ada' }}
            </span>
        </p>

        <div class="d-flex justify-content-between align-items-center mt-3 mb-3 pt-3 border-top">
            <span class="fw-bold text-dark" style="font-size: 1.25rem;">
                Rp{{ number_format($harga, 0, ',', '.') }}
            </span>
            <span class="badge {{ $status == 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        
        {{-- Tombol Pesan --}}
        <div class="d-grid mt-3">
            <a href="{{ route('hotel.reservasi', ['id' => $id]) }}" 
            class="btn btn-primary rounded-pill py-2 {{ $status !== 'tersedia' ? 'disabled' : '' }}">
                Pesan
            </a>
        </div>
    </div>
</div>
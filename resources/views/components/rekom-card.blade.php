<div class="card shadow-sm rounded-4" style="width: 260px; flex: 0 0 auto;">
    <img src="{{ asset('images/'.$gambar) }}" 
         class="card-img-top rounded-top-4" 
         alt="{{ $namaHotel }}" 
         style="height: 160px; object-fit: cover;">

    <div class="card-body">
        <div class="d-flex align-items-center mb-2">
            <span class="badge bg-success me-2">{{ $rating }}</span>
             <span class="text-warning fw-bold">★ {{ $bintang }}</span>
        </div>
        <h6 class="card-title fw-semibold mb-1">{{ $namaHotel }}</h6>
        <p class="text-muted mb-0">{{ $kota }}</p>
    </div>
</div>

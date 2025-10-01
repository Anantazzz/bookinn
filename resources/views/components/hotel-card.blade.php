<div class="d-flex align-items-stretch bg-white shadow-sm rounded-4 mb-3" style="overflow:hidden;">
    {{-- Foto Hotel --}}
    <div style="width: 200px; height: 140px; flex-shrink:0;">
        <img src="{{ asset('images/'.$gambar) }}" 
             alt="{{ $namaHotel }}" 
             class="w-100 h-100" 
             style="object-fit: cover;">
    </div>

    {{-- Info Hotel --}}
    <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
        <div>
            <h5 class="fw-semibold mb-1">{{ $namaHotel }}</h5>
            <p class="text-muted mb-2">{{ $kota }}</p>
        </div>

        {{-- Rating --}}
        <div class="d-flex align-items-center">
            <span class="badge bg-success me-2">{{ $rating }}</span>
        </div>
    </div>

    {{-- Bintang Hotel --}}
    <div class="p-3 text-end">
        <span class="text-warning fw-bold">★ {{ $bintang }}</span>
    </div>
</div>

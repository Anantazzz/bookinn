@props([
    'id' => null,
    'gambar' => 'default-hotel.jpg',
    'nama_hotel' => 'Hotel Tanpa Nama', 
    'kota' => 'Kota Tidak Diketahui',
    'alamat' => 'Alamat tidak tersedia',
    'rating' => 0,
    'bintang' => 0
])

<section {{ $attributes->merge(['class' => 'relative rounded-xl overflow-hidden shadow-lg mb-5']) }}>
    <img src="{{ asset('images/'.$gambar) }}" 
         alt="{{ $nama_hotel }}" 
         class="w-full h-64 object-cover">

    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="text-center text-white">
            <h1 class="text-3xl font-bold">{{ $nama_hotel }}</h1>
            <p class="text-sm">{{ $alamat }}, {{ $kota }}</p>
        </div>
    </div>
</section>

{{-- Info Hotel --}}
<div class="p-4">
    <h2 class="fw-bold">{{ $nama_hotel }}</h2>
    <p class="text-muted mb-1">{{ $kota }}</p>
    <p class="text-muted">{{ $alamat }}</p>

    <div class="d-flex align-items-center gap-3 mt-2">
        <span class="badge bg-success">{{ number_format($rating, 2) }}</span>
        <span class="text-warning fw-bold">★ {{ $bintang }}</span>
    </div>
</div>
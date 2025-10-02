@props(['gambar' => null, 'tipeKamar' => '', 'harga' => 0, 'status' => '', 'kapasitas' => 0, 'jumlahBed' => 0, 'internet' => false])

<div class="card shadow-sm rounded-4 mb-3" style="width: 100%;">
    <img src="{{ asset('images/' . $gambar) }}" 
         class="card-img-top rounded-top-4" 
         alt="{{ $tipeKamar }}" 
         style="height: 160px; object-fit: cover;">

    <div class="card-body">
        <h6 class="card-title fw-semibold mb-1">{{ $tipeKamar }}</h6>
        <p class="text-muted mb-1">Kapasitas: {{ $kapasitas }} orang</p>
        <p class="text-muted mb-1">Jumlah Bed: {{ $jumlahBed }}</p>
        <p class="text-muted mb-1">
            Internet: 
            <span class="{{ $internet ? 'text-success' : 'text-danger' }}">
                {{ $internet ? 'Tersedia' : 'Tidak Ada' }}
            </span>
        </p>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="fw-bold text-dark">Rp{{ number_format($harga, 0, ',', '.') }}</span>
            <span class="badge {{ $status == 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                {{ ucfirst($status) }}
            </span>
        </div>
    </div>
</div>

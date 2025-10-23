@props([
    'id' => null, 
    'gambar' => null, 
    'tipeKamar' => '', 
    'harga' => 0, 
    'kapasitas' => 0, 
    'jumlahBed' => 0, 
    'internet' => false, 
    'sisaKamar' => 0,
    'tanggalCheckin' => null,
    'tanggalCheckout' => null
])

<div class="card shadow-sm rounded-4 mb-3" style="width: 100%;">
    <img src="{{ asset('images/kamars/' . $gambar) }}" 
         class="card-img-top rounded-top-4" 
         alt="{{ $tipeKamar }}" 
         style="height: 160px; object-fit: cover;">

    <div class="card-body">
        <h6 class="card-title fw-bold mb-3" style="font-size: 1.4rem;">{{ $tipeKamar }}</h6>

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
        </div>

        <div class="text-end mt-1">
            @if($sisaKamar === 0)
                <small class="px-2 py-1 rounded-pill text-white" style="background-color: #ef4444;">
                    Habis
                </small>
            @elseif($sisaKamar <= 4)
                <small class="px-2 py-1 rounded-pill text-white" style="background-color: #f59e0b;">
                    Tersisa {{ $sisaKamar }} kamar
                </small>
            @endif
        </div>

        <div class="d-grid mt-3">
            @php
                // Build URL dengan query string untuk passing tanggal
                $url = $sisaKamar > 0 ? route('hotel.reservasi', ['id' => $id]) : '#';
                
                // Tambahkan query parameter jika ada tanggal
                if ($sisaKamar > 0 && $tanggalCheckin && $tanggalCheckout) {
                    $url .= '?tanggal_checkin=' . $tanggalCheckin . '&tanggal_checkout=' . $tanggalCheckout;
                }
            @endphp

            <a href="{{ $url }}" 
               class="btn btn-primary rounded-pill py-2 {{ $sisaKamar <= 0 ? 'disabled' : '' }}">
                Pesan
            </a>
        </div>
    </div>
</div>
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
        </div>
    </div>
</section>

{{-- Filter Tanggal Check-in/Check-out --}}
<section class="mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-calendar-check me-2"></i>Cek Ketersediaan Kamar
            </h5>
            
            <form action="{{ route('hotel.detail', $hotel->id) }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tanggal Check-in</label>
                    <input type="date" 
                           name="tanggal_checkin" 
                           class="form-control" 
                           value="{{ $tanggalCheckin ?? '' }}"
                           min="{{ date('Y-m-d') }}"
                           required>
                </div>
                
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tanggal Check-out</label>
                    <input type="date" 
                           name="tanggal_checkout" 
                           class="form-control" 
                           value="{{ $tanggalCheckout ?? '' }}"
                           min="{{ $tanggalCheckin ?? date('Y-m-d') }}"
                           required>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cek
                    </button>
                </div>
            </form>

            @if($tanggalCheckin && $tanggalCheckout)
                <div class="alert alert-info mt-3 mb-0 d-flex align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <span>
                        Menampilkan ketersediaan untuk <strong>{{ \Carbon\Carbon::parse($tanggalCheckin)->format('d M Y') }}</strong> 
                        sampai <strong>{{ \Carbon\Carbon::parse($tanggalCheckout)->format('d M Y') }}</strong>
                    </span>
                </div>
            @else
                <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <span>Pilih tanggal untuk melihat ketersediaan kamar yang akurat</span>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- List Kamar Section --}}
<section class="mt-5">
    <h2 class="fw-bold mb-4" style="font-size: 1.5rem;">Pilih kamar Anda</h2>
    
    @if(count($kamarData) > 0)
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
                        :tanggalCheckin="$tanggalCheckin ?? null"
                        :tanggalCheckout="$tanggalCheckout ?? null"
                    />
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">
            <i class="bi bi-info-circle me-2"></i>
            Tidak ada kamar tersedia untuk hotel ini.
        </div>
    @endif
</section>

@endsection

@push('scripts')
<script>
    // Auto-update min checkout date when checkin changes
    document.querySelector('input[name="tanggal_checkin"]').addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const checkoutInput = document.querySelector('input[name="tanggal_checkout"]');
        
        // Set minimum checkout date to 1 day after checkin
        checkinDate.setDate(checkinDate.getDate() + 1);
        const minCheckout = checkinDate.toISOString().split('T')[0];
        checkoutInput.setAttribute('min', minCheckout);
        
        // If current checkout is before new minimum, update it
        if (checkoutInput.value && checkoutInput.value < minCheckout) {
            checkoutInput.value = minCheckout;
        }
    });
</script>
@endpush
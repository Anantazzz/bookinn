@extends('layouts.app')

@section('content')

{{-- Header Hotel dengan Gambar --}}
<section class="mb-5">
    <div class="position-relative rounded-4 overflow-hidden shadow-lg" style="height: 400px;">
        <img src="{{ asset('images/'.$hotel->gambar) }}" 
             alt="{{ $hotel->nama_hotel }}" 
             class="w-100 h-100" 
             style="object-fit: cover;">
        
        {{-- Gradient Overlay --}}
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
        
        {{-- Rating Badge --}}
        <div class="position-absolute top-0 end-0 m-4">
            <div class="bg-white rounded-pill px-4 py-2 shadow-lg">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span class="fw-bold fs-5">{{ $hotel->bintang }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Info Hotel --}}
<section class="mb-5">
    <div class="bg-white rounded-4 shadow-sm border-0 p-4">
        <h1 class="fw-bold mb-3" style="font-size: 2.5rem; color: #1a202c;">{{ $hotel->nama_hotel }}</h1>
        
        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center text-muted">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="bi bi-geo-alt-fill text-primary"></i>
                </div>
                <div>
                    <p class="mb-0 fw-semibold text-dark">{{ $hotel->kota }}</p>
                    <p class="mb-0 small text-muted">{{ $hotel->alamat }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Filter Tanggal Check-in/Check-out --}}
<section class="mb-5">
    <div class="bg-white rounded-4 shadow-lg border-0 overflow-hidden">
        <div class="bg-gradient-to-r p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h4 class="fw-bold text-white mb-0">
                <i class="bi bi-calendar-check me-2"></i>Cek Ketersediaan Kamar
            </h4>
            <p class="text-white opacity-75 mb-0 mt-1 small">Pilih tanggal untuk melihat kamar yang tersedia</p>
        </div>
        
        <div class="p-4">
            <form action="{{ route('hotel.detail', $hotel->id) }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-dark mb-2">
                        <i class="bi bi-box-arrow-in-right text-success me-2"></i>Tanggal Check-in
                    </label>
                    <input type="date" 
                           name="tanggal_checkin" 
                           class="form-control form-control-lg rounded-3 border-2" 
                           value="{{ $tanggalCheckin ?? '' }}"
                           min="{{ date('Y-m-d') }}"
                           style="border-color: #e2e8f0;"
                           required>
                </div>
                
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-dark mb-2">
                        <i class="bi bi-box-arrow-right text-danger me-2"></i>Tanggal Check-out
                    </label>
                    <input type="date" 
                           name="tanggal_checkout" 
                           class="form-control form-control-lg rounded-3 border-2" 
                           value="{{ $tanggalCheckout ?? '' }}"
                           min="{{ $tanggalCheckin ?? date('Y-m-d') }}"
                           style="border-color: #e2e8f0;"
                           required>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-search me-2"></i>Cek
                    </button>
                </div>
            </form>

            @if($tanggalCheckin && $tanggalCheckout)
                <div class="alert alert-info border-0 mt-4 mb-0 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);">
                    <div class="d-flex align-items-start">
                        <div class="bg-info bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-info-circle-fill text-info"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold text-dark">Periode Menginap</p>
                            <p class="mb-0 text-muted small">
                                <strong>{{ \Carbon\Carbon::parse($tanggalCheckin)->format('d M Y') }}</strong> 
                                sampai <strong>{{ \Carbon\Carbon::parse($tanggalCheckout)->format('d M Y') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning border-0 mt-4 mb-0 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold text-dark">Pilih Tanggal</p>
                            <p class="mb-0 text-muted small">Pilih tanggal untuk melihat ketersediaan kamar yang akurat</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- List Kamar Section --}}
<section class="mt-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pilih Kamar Anda</h3>
            <p class="text-muted mb-0">{{ count($kamarData) }} tipe kamar tersedia</p>
        </div>
        
        @if(count($kamarData) > 0)
            <div class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                <i class="bi bi-check-circle-fill me-1"></i>
                Kamar Tersedia
            </div>
        @endif
    </div>
    
    @if(count($kamarData) > 0)
        <div class="row g-4">
            @foreach ($kamarData as $kamar)
                <div class="col-lg-4 col-md-6">
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
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Tidak Ada Kamar Tersedia</h5>
            <p class="text-muted mb-4">Saat ini tidak ada kamar tersedia untuk hotel ini</p>
            <a href="{{ route('hotel') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Cari Hotel Lain
            </a>
        </div>
    @endif
</section>

{{-- Custom Styles --}}
<style>
    /* Form Control Focus */
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    /* Button Hover */
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3) !important;
    }

    /* Alert Animations */
    .alert {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Room Card Container */
    .row.g-4 {
        perspective: 1000px;
    }

    /* Smooth Transitions */
    * {
        transition: all 0.3s ease;
    }
</style>

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

    // Add animation to form inputs
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
</script>
@endpush
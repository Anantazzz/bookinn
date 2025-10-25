@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold mb-2" style="font-size: 2rem; color: #1a1a1a;">Riwayat Reservasi</h2>
    <p class="text-muted">Kelola dan pantau semua reservasi Anda</p>
  </div>

  <div class="row gy-4">
    @foreach ($reservasis as $reservasi)
      @php
        $statusColors = [
          'pending' => 'bg-warning text-dark',
          'aktif'   => 'bg-primary text-white',
          'selesai' => 'bg-success text-white',
          'batal'   => 'bg-danger text-white',
        ];

        $pembatalan = \App\Models\Pembatalan::where('reservasi_id', $reservasi->id)->first();
      @endphp

      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden transition-all" 
             style="transition: transform 0.2s, box-shadow 0.2s;"
             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
          
          <div class="card-body p-4">
            <div class="row align-items-center">
              
              {{-- Gambar Hotel --}}
              <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                <div class="position-relative rounded-3 overflow-hidden" style="height: 160px;">
                  <img 
                    src="{{ asset('images/' . optional($reservasi->kamar->hotel)->gambar) }}" 
                    alt="Gambar Kamar" 
                    class="w-100 h-100"
                    style="object-fit: cover;"
                  >
                  <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge {{ $statusColors[$reservasi->status] ?? 'bg-secondary' }} px-3 py-2 rounded-pill fw-semibold">
                      {{ ucfirst($reservasi->status) }}
                    </span>
                  </div>
                </div>
              </div>

              {{-- Info Reservasi --}}
              <div class="col-lg-6 col-md-5 mb-3 mb-md-0">
                <h5 class="fw-bold mb-2" style="color: #1a1a1a; font-size: 1.25rem;">
                  {{ optional($reservasi->kamar->hotel)->nama_hotel ?? 'Tidak tersedia' }}
                </h5>
                
                <div class="d-flex flex-column gap-2">
                  <div class="d-flex align-items-center text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                      <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    <span style="font-size: 0.95rem;">{{ $reservasi->kamar->tipeKamar->nama_tipe ?? '-' }}</span>
                  </div>

                  <div class="d-flex align-items-center text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                      <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                    </svg>
                    <span style="font-size: 0.95rem; font-weight: 500;">
                      @if ($reservasi->pembayaran && $reservasi->pembayaran->invoice)
                        Rp {{ number_format($reservasi->pembayaran->invoice->total, 0, ',', '.') }}
                      @else
                        <span class="text-muted fst-italic">Belum ada invoice</span>
                      @endif
                    </span>
                  </div>

                  <div class="d-flex align-items-center text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                      <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                    <span style="font-size: 0.95rem;">
                      {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d M Y') }} - 
                      {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d M Y') }}
                    </span>
                  </div>
                </div>
              </div>

              {{-- Tombol Aksi --}}
              <div class="col-lg-3 col-md-3">
                <div class="d-flex flex-column gap-2">
                  @if($reservasi->status !== 'batal' && $reservasi->pembayaran && $reservasi->pembayaran->invoice)
                    <a href="{{ route('invoice.show', $reservasi->pembayaran->invoice->id) }}" 
                       class="btn btn-outline-primary rounded-pill py-2 fw-semibold"
                       style="transition: all 0.2s;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                      </svg>
                      Lihat Invoice
                    </a>
                  @elseif($reservasi->status === 'batal' && $pembatalan)
                    <a href="{{ route('pembatalan.show', $pembatalan->id) }}" 
                       class="btn btn-outline-danger rounded-pill py-2 fw-semibold"
                       style="transition: all 0.2s;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                      </svg>
                      Lihat Struk Pembatalan
                    </a>
                  @endif

                  @if ($reservasi->status === 'pending' && now()->lt(\Carbon\Carbon::parse($reservasi->tanggal_checkin)->subDay()))
                    <a href="{{ route('pembatalan.form', $reservasi->id) }}" 
                       class="btn btn-danger rounded-pill py-2 fw-semibold"
                       style="transition: all 0.2s;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                      </svg>
                      Batalkan Booking
                    </a>
                  @endif
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<style>
  .transition-all {
    transition: all 0.2s ease-in-out;
  }
  
  .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }
</style>
@endsection
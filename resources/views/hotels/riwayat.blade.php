@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h2 class="fw-bold text-center mb-4">Riwayat Reservasi</h2>

  <div class="row gy-4">
    @foreach ($reservasis as $reservasi)
      @php
        $statusColors = [
          'pending' => 'badge bg-warning text-dark',
          'aktif'   => 'badge bg-primary',
          'selesai' => 'badge bg-success',
          'batal'   => 'badge bg-danger',
        ];

        $pembatalan = \App\Models\Pembatalan::where('reservasi_id', $reservasi->id)->first();
      @endphp

      <div class="card shadow-sm border-0 rounded-4 hover-shadow mb-3">
        <div class="card-body d-flex justify-content-between align-items-start">
          
          {{-- Kiri: Info Hotel --}}
          <div class="d-flex align-items-center">
            <img 
              src="{{ asset('images/' . optional($reservasi->kamar->hotel)->gambar) }}" 
              alt="Gambar Kamar" 
              class="rounded-3 me-3"
              style="width: 180px; height: 120px; object-fit: cover;"
            >

            <div>
              <h5 class="fw-semibold mb-1">{{ optional($reservasi->kamar->hotel)->nama_hotel ?? 'Tidak tersedia' }}</h5>
              <p class="mb-1 text-muted">Tipe Kamar : {{ $reservasi->kamar->tipeKamar->nama_tipe ?? '-' }}</p>
              <p class="mb-2 text-muted">
                Total : 
                @if ($reservasi->pembayaran && $reservasi->pembayaran->invoice)
                    Rp {{ number_format($reservasi->pembayaran->invoice->total, 0, ',', '.') }}
                @else
                    <span class="text-muted">Belum ada invoice</span>
                @endif
              </p >
              <p class="mb- text-muted">
                Tanggal : {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d M Y') }} - 
                {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d M Y') }}
              </p>

              {{-- Link ke invoice / struk --}}
              @if($reservasi->status !== 'batal' && $reservasi->pembayaran && $reservasi->pembayaran->invoice)
                  <a href="{{ route('invoice.show', $reservasi->pembayaran->invoice->id) }}" 
                    class="btn btn-sm btn-outline-primary">
                    Lihat Invoice
                  </a>
              @elseif($reservasi->status === 'batal' && $pembatalan)
                  <a href="{{ route('pembatalan.show', $pembatalan->id) }}" 
                    class="btn btn-sm btn-outline-danger">
                    Lihat Struk Pembatalan
                  </a>
              @endif
            </div>
          </div>

          {{-- Kanan: Status + Tombol Pembatalan --}}
          <div class="d-flex flex-column align-items-end justify-content-start" style="min-width: 200px;">
            <span class="{{ $statusColors[$reservasi->status] ?? 'badge bg-secondary' }}">
              {{ ucfirst($reservasi->status) }}
            </span>

            {{-- Tombol Batalkan Booking (di bawah status) --}}
            @if ($reservasi->status === 'pending' && now()->lt(\Carbon\Carbon::parse($reservasi->tanggal_checkin)->subDay()))
              <a href="{{ route('pembatalan.form', $reservasi->id) }}" 
                 class="btn btn-sm btn-danger mt-5">
                Batalkan Booking
              </a>
            @endif
          </div>

        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection

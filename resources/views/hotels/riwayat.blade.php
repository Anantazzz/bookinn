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
      @endphp

      {{-- 🔗 UBAHAN: Bungkus card dalam tag <a> supaya bisa diklik --}}
      @if($reservasi->pembayaran && $reservasi->pembayaran->invoice)
        <a href="{{ route('invoice.show', $reservasi->pembayaran->invoice->id) }}" 
           class="text-decoration-none text-dark">
      @else
        <a href="#" class="text-decoration-none text-muted" onclick="return false;">
      @endif
      {{-- 🔗 END UBAHAN --}}

      <div class="card shadow-sm border-0 rounded-4 hover-shadow">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <img 
              src="{{ asset('images/' . $reservasi->kamar->hotel->gambar) }}" 
              alt="Gambar Kamar" 
              class="rounded-3 me-3"
              style="width: 180px; height: 120px; object-fit: cover;"
            >

            <div>
              <h5 class="fw-semibold mb-1">{{ $reservasi->kamar->hotel->nama_hotel }}</h5>
              <p class="mb-1 text-muted">Tipe Kamar: {{ $reservasi->tipe_kamar->nama_tipe }}</p>
              <p class="mb-1 text-muted">
                Total: 
                @if ($reservasi->pembayaran && $reservasi->pembayaran->invoice)
                    Rp {{ number_format($reservasi->pembayaran->invoice->total, 0, ',', '.') }}
                @else
                    <span class="text-muted">Belum ada invoice</span>
                @endif
              </p>
              <p class="mb-0 text-muted">
                Tanggal: {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d M Y') }} - 
                {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d M Y') }}
              </p>
            </div>
          </div>

          <span class="{{ $statusColors[$reservasi->status] ?? 'badge bg-secondary' }}">
            {{ ucfirst($reservasi->status) }}
          </span>
        </div>
      </div>

      </a> {{-- 🔗 Tutup tag link --}}
    @endforeach
  </div>
</div>
@endsection

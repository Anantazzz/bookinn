@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="card shadow-sm border-0 rounded-4 p-4">
    <h4 class="text-center text-danger fw-bold mb-4">Form Pembatalan Reservasi</h4>

    {{-- Detail Reservasi --}}
    <div class="mb-4">
      <h5 class="fw-semibold mb-3">Detail Reservasi :</h5>
      <div class="ps-2">
        <p class="mb-1"><strong>Nama Hotel:</strong> {{ $reservasi->kamar->hotel->nama_hotel ?? '-' }}</p>
        <p class="mb-1"><strong>Tipe Kamar:</strong> {{ $reservasi->tipe_kamar->nama_tipe ?? '-' }}</p>
        <p class="mb-1"><strong>Tanggal Check-in:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d M Y') }}</p>
        <p class="mb-1"><strong>Tanggal Check-out:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d M Y') }}</p>
        <p class="mb-1"><strong>Jumlah Bayar:</strong> Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</p>
        <p class="mb-0"><strong>Status Reservasi:</strong> 
          <span class="badge bg-warning text-dark text-capitalize">{{ $reservasi->status }}</span>
        </p>
      </div>
    </div>
    <hr>

    {{-- Form Pembatalan --}}
    <form action="{{ route('pembatalan.store', $reservasi->id) }}" method="POST">
      @csrf
      <div class="mb-4">
        <label for="alasan" class="form-label fw-semibold">Pilih Alasan Pembatalan</label>
        <select name="alasan" id="alasan" class="form-select" required>
          <option value="">-- Pilih Alasan --</option>
          <option value="Perubahan rencana perjalanan">Perubahan rencana perjalanan</option>
          <option value="Perubahan tanggal">Perubahan tanggal</option>
          <option value="Alasan keuangan">Alasan keuangan</option>
          <option value="Masalah pribadi">Masalah pribadi</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>

      {{-- Tombol --}}
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('riwayat') }}" class="btn btn-secondary px-4">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-danger px-4">
          <i class="bi bi-x-circle"></i> Batalkan Sekarang
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

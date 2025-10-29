@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h4 class="text-center text-danger fw-bold mb-4">Form Pembatalan Reservasi</h4>

    <div class="row g-4">
      {{-- Kiri: Detail Reservasi --}}
      <div class="col-md-6">
        <div class="card shadow-sm p-3 border border-1 border-secondary-subtle rounded-3 h-100">
          <h5 class="fw-semibold mb-3 text-center text-primary">Detail Reservasi</h5>
          <div class="ps-2">
            <p class="mb-2"><strong>Nama Hotel:</strong> {{ $reservasi->kamar->hotel->nama_hotel ?? '-' }}</p>
            <p class="mb-2"><strong>Tipe Kamar:</strong> {{ $reservasi->kamar->tipeKamar->nama_tipe ?? '-' }}</p>
            <p class="mb-2"><strong>Tanggal Check-in:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d M Y') }}</p>
            <p class="mb-2"><strong>Tanggal Check-out:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d M Y') }}</p>
            <p class="mb-2"><strong>Jumlah Bayar:</strong> Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</p>
            <p class="mb-0"><strong>Status Reservasi:</strong> 
              <span class="badge 
                {{ $reservasi->status == 'pending' ? 'bg-warning text-dark' : '' }}
                {{ $reservasi->status == 'aktif' ? 'bg-primary' : '' }}
                {{ $reservasi->status == 'selesai' ? 'bg-success' : '' }}
                {{ $reservasi->status == 'batal' ? 'bg-danger' : '' }}
                text-capitalize">{{ $reservasi->status }}</span>
            </p>
          </div>
        </div>
      </div>

      {{-- Kanan: Form Alasan Pembatalan --}}
      <div class="col-md-6">
        <div class="card shadow-sm p-3 border border-1 border-secondary-subtle rounded-3 h-100">
          <h5 class="fw-semibold mb-3 text-center text-primary">Alasan Pembatalan</h5>
          <form action="{{ route('pembatalan.store', $reservasi->id) }}" method="POST">
            @csrf
            <div class="mb-4">
              <label for="alasan" class="form-label fw-semibold">Alasan Pembatalan</label>
              <select name="alasan" id="alasan" class="form-select" required>
                <option value="">-- Pilih Alasan --</option>
                <option value="Perubahan rencana perjalanan">Perubahan rencana perjalanan</option>
                <option value="Alasan keuangan">Alasan keuangan</option>
                <option value="Masalah pribadi">Masalah pribadi</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>

           <div class="d-flex justify-content-end gap-2 mt-5">
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
    </div>
  </div>
</div>
@endsection

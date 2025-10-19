@extends('layouts.app')

@section('title', 'Form Pembayaran')

@section('content')
<div class="row g-4 justify-content-center">
  <div class="col-lg-8" style="max-width: 600px;">
    <div class="card p-4 mb-4">
      <h5 class="section-title">
        <i class="bi bi-credit-card me-2"></i>Langkah 2: Rincian Pembayaran
      </h5>

      <div class="card shadow-sm">
        <div class="card-body">

          {{-- Tampilkan total harga --}}
          @if(isset($reservasi))
            <div class="alert alert-info mb-4">
              <strong>Total Bayar:</strong> Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}
            </div>
          @endif

          <form method="POST" action="{{ route('hotel.prosesPembayaran', ['id' => $kamar->id]) }}">
            @csrf

            {{-- Atas Nama --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Atas Nama Rekening</label>
              <input type="text" name="atas_nama" class="form-control" placeholder="Nama sesuai rekening" required>
            </div>

            {{-- Pilih Bank --}}
            <div class="mb-3" id="bank-section">
              <label class="form-label fw-semibold">Bank Tujuan</label>
              <select name="bank" class="form-select">
                <option value="">-- Pilih Bank --</option>
                <option value="mandiri">Mandiri</option>
                <option value="bca">BCA</option>
                <option value="bri">BRI</option>
                <option value="bni">BNI</option>
              </select>
            </div>

            {{-- Nomor Rekening Pengirim --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Nomor Rekening Pengirim</label>
              <input type="text" name="nomor_rekening" class="form-control" placeholder="Masukkan nomor rekening Anda" maxlength="20" required>
            </div>

            {{-- Hidden field kamar --}}
            <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">

            <div class="d-grid mt-3">
              <button type="submit" class="btn btn-primary rounded-pill py-2">
                Pesan Sekarang
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

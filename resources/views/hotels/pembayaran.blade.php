@extends('layouts.app')

@section('title', 'Form Pembayaran')

@section('content')

<div class="row g-4 justify-content-center">
  {{-- Kolom Kanan / Langkah 2 --}}
  <div class="col-lg-8" style="max-width: 600px;">
    <div class="card p-4 mb-4">
      <h5 class="section-title">
        <i class="bi bi-credit-card me-2"></i>Langkah 2: Rincian Pembayaran
      </h5>
      
      <div class="card shadow-sm">
        <div class="card-body">

          @if(isset($reservasi))
          <div class="alert alert-info mb-4">
            <strong>Total Bayar:</strong> Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}
          </div>
        @endif

          <form method="POST" action="{{ route('hotel.prosesPembayaran', ['id' => $kamar->id]) }}">
            @csrf

            <div class="mb-3 d-flex justify-content-between align-items-center">
              <label class="form-label mb-0">*Kolom wajib</label>
              <div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" width="40" alt="mastercard">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" width="40" alt="visa">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Pemegang Rekening (pengirim)</label>
              <input type="text" name="nama_pengirim" class="form-control" placeholder="Nama sesuai rekening (opsional)">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Pilih Bank (Transfer)</label>
              <select name="bank" class="form-select" required>
                <option value="">-- Pilih Bank --</option>
                <option value="mandiri">Mandiri</option>
                <option value="bca">BCA</option>
                <option value="bni">BNI</option>
                <option value="bri">BRI</option>
                <option value="cimb">CIMB Niaga</option>
                <option value="dbs">DBS</option>
                <option value="btn">BTN</option>
                <option value="other">Bank Lainnya</option>
              </select>
            </div>

           <div class="mb-3">
              <label class="form-label fw-semibold">Nomor Rekening</label>
              <input type="text" name="nomor_rekening" class="form-control" placeholder="Masukkan nomor rekening Anda" maxlength="20">
           </div>

            <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">

            <div class="d-grid mt-3">
              <button type="submit" class="btn btn-primary rounded-pill py-2">Pesan Sekarang</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

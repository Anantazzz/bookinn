@extends('layouts.app')

@section('title', 'Form Pembayaran')

@section('content')
<div class="row g-4 justify-content-center">
  <!-- Kolom Kiri -->
  <div class="col-lg-8" style="max-width: 600px;"> 
    <!-- Langkah 1 -->
    <div class="card p-4 mb-4">
      <h5 class="section-title">
        <i class="bi bi-person-lines-fill me-2"></i>Langkah 1: Rincian Anda
      </h5>
      <p class="small-text">Masukkan nama tamu yang akan menginap, sesuai dengan data login Anda.</p>
      
      <form>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" class="form-control" value="{{ $user->name }}" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Alamat Email</label>
          <input type="email" class="form-control" value="{{ $user->email }}" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Alamat Rumah</label>
          <input type="text" class="form-control" value="{{ $user->alamat }}" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nomor Ponsel</label>
          <input type="text" class="form-control" value="{{ $user->no_hp }}" readonly>
        </div>
      </form>
    </div>
  </div>

  {{-- Form kanan --}}
<div class="col-lg-4">
  <div class="card p-4 mb-3">
    <h6 class="fw-bold mb-3">Kamar {{ $kamar->tipeKamar->nama_tipe }}</h6>
    
    <div class="mb-2">
      <label class="form-label fw-semibold">Tanggal Check-in</label>
      <input type="date" name="tanggal_checkin" class="form-control" required>
    </div>

    <div class="mb-2">
      <label class="form-label fw-semibold">Jam Check-in</label>
      <input type="time" name="jam_checkin" class="form-control" required>
    </div>

    <div class="mb-2">
      <label class="form-label fw-semibold">Tanggal Check-out</label>
      <input type="date" name="tanggal_checkout" class="form-control" required>
    </div>

    <div class="mb-2">
      <label class="form-label fw-semibold">Jam Check-out</label>
      <input type="time" name="jam_checkout" class="form-control" required>
    </div>

    <p class="mt-3"><strong>Harga: Rp{{ number_format($kamar->harga, 0, ',', '.') }}</strong></p>

    <div class="d-grid mt-3">
         <a href="{{ route('hotel.pembayaran', ['id' => $kamar->id]) }}" class="btn btn-primary rounded-pill py-2 text-center">
          Lanjutkan ke Pembayaran
      </a>
    </div>
  </div>
</div>

    <div class="card p-4 border-danger-subtle">
      <h6 class="fw-bold mb-2 text-dark">Kebijakan pembatalan</h6>
      <ul class="small-text mb-2">
        <li>Check-in di jam 14:00 & Check-out di jam 12:00.</li>
        <li>Pembatalan tanpa biaya hingga H-1 sebelum check-in.</li>
        <li>Kami pastikan uang kembali 100% jika pembatalan dilakukan h-1 Check-in</li>
      </ul>
      <p class="text-danger small-text">
        Kami tidak dapat mengembalikan pembayaran jika Anda tidak datang atau check-out lebih awal.
      </p>
    </div>
  </div>
</div>
@endsection

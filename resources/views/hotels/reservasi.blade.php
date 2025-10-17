@extends('layouts.app')

@section('title', 'Form Reservasi')

@section('content')

<div class="row g-4 justify-content-center">
  <div class="col-lg-10" style="max-width: 900px;"> 
    <form action="{{ route('reservasi.store', ['id' => $kamar->id]) }}" method="POST">
      @csrf

      <!-- Langkah 1: Data User -->
      <div class="card p-4 mb-4 shadow-sm border border-2 border-secondary-subtle rounded-3">
        <h5 class="fw-semibold mb-3 text-dark">
          <i class="bi bi-person-lines-fill me-2"></i>Langkah 1: Rincian Anda
        </h5>
        <p class="small text-muted mb-4">Data ini otomatis diambil dari akun Anda yang sedang login.</p>

        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control" value="{{ $user->name }}" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Alamat Email</label>
          <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Alamat Rumah</label>
          <input type="text" name="alamat" class="form-control" value="{{ $user->alamat }}" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Nomor Ponsel</label>
          <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}" readonly>
        </div>
      </div>

      <!-- Langkah 2: Data Kamar -->
      <div class="card p-4 mb-4 shadow-sm border border-2 border-secondary-subtle rounded-3">
        <h5 class="fw-semibold mb-3 text-dark">
          <i class="bi bi-building me-2"></i>Langkah 2: Detail Kamar
        </h5>

        <h6 class="fw-semibold text-secondary mb-3">Kamar {{ $kamar->tipeKamar->nama_tipe }}</h6>

        <div class="mb-3">
          <label class="form-label fw-semibold">Tanggal Check-in <span class="text-danger">*</span></label>
          <input type="date" 
                 name="tanggal_checkin" 
                 class="form-control @error('tanggal_checkin') is-invalid @enderror" 
                 value="{{ old('tanggal_checkin', $tanggalCheckin) }}"
                 min="{{ date('Y-m-d') }}"
                 required>
          @error('tanggal_checkin')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Jam Check-in <span class="text-danger">*</span></label>
          <input type="time" 
                 name="jam_checkin" 
                 class="form-control @error('jam_checkin') is-invalid @enderror" 
                 value="{{ old('jam_checkin', '14:00') }}"
                 required>
          @error('jam_checkin')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Waktu check-in standar: 14:00</small>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Tanggal Check-out <span class="text-danger">*</span></label>
          <input type="date" 
                 name="tanggal_checkout" 
                 class="form-control @error('tanggal_checkout') is-invalid @enderror" 
                 value="{{ old('tanggal_checkout', $tanggalCheckout) }}"
                 min="{{ $tanggalCheckin ?? date('Y-m-d', strtotime('+1 day')) }}"
                 required>
          @error('tanggal_checkout')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Jam Check-out <span class="text-danger">*</span></label>
          <input type="time" 
                 name="jam_checkout" 
                 class="form-control @error('jam_checkout') is-invalid @enderror" 
                 value="{{ old('jam_checkout', '12:00') }}"
                 required>
          @error('jam_checkout')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Waktu check-out standar: 12:00</small>
        </div>

        <p class="mt-3 mb-0 fw-semibold fs-6 text-dark">
          Harga per malam: Rp{{ number_format($kamar->harga, 0, ',', '.') }}
        </p>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" 
               type="checkbox" 
               name="kasur_tambahan" 
               id="kasur_tambahan" 
               value="1"
               {{ old('kasur_tambahan') ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="kasur_tambahan">
          Tambah kasur (+Rp100.000)
        </label>
      </div>
      
      <!-- Langkah 3: Tombol Submit -->
      <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary rounded-pill py-2">
          Lanjutkan ke Pembayaran
        </button>
      </div>
    </form>

    <!-- Kebijakan Pembatalan -->
    <div class="card p-4 border border-2 border-secondary-subtle rounded-3 bg-light-subtle">
      <h6 class="fw-semibold mb-2 text-dark">Kebijakan Pembatalan</h6>
      <ul class="small mb-2 text-secondary">
        <li>Check-in pukul 14:00 & Check-out pukul 12:00.</li>
        <li>Pembatalan gratis hingga H-1 sebelum check-in.</li>
        <li>Kami pastikan uang kembali 100% jika melakukan pembatalan sesuai kebijakan.</li>
      </ul>
      <p class="text-danger small mb-0">
        Tidak ada pengembalian pembayaran jika Anda tidak datang atau check-out lebih awal.
      </p>
    </div>
  </div>
</div>
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
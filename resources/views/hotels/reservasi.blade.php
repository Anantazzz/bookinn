@extends('layouts.app')

@section('title', 'Form Reservasi')

@section('content')

<div class="min-vh-100 py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10" style="max-width: 900px;">
        
        {{-- Header --}}
        <div class="text-center mb-5">
          <div class="d-inline-flex align-items-center gap-3 mb-3">
            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
              <i class="bi bi-calendar-check-fill text-primary fs-3"></i>
            </div>
            <div class="text-start">
              <h2 class="fw-bold mb-0">Form Reservasi</h2>
              <p class="text-muted mb-0 small">Lengkapi data untuk melanjutkan booking</p>
            </div>
          </div>
        </div>

        <form action="{{ route('reservasi.store', ['id' => $kamar->id]) }}" method="POST">
          @csrf

          {{-- Langkah 1: Data User --}}
          <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-4">
              <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                  <i class="bi bi-person-lines-fill text-primary fs-5"></i>
                </div>
                <div>
                  <h5 class="fw-bold mb-1">Langkah 1: Rincian Anda</h5>
                  <p class="text-muted mb-0 small">Data ini otomatis diambil dari akun Anda yang sedang login</p>
                </div>
              </div>
            </div>
            
            <div class="card-body p-4">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-person-fill text-primary me-2"></i>Nama Lengkap 
                  </label>
                  <input type="text" name="nama" class="form-control form-control-lg rounded-3" value="{{ $user->name }}" readonly style="background-color: #f8f9fa; border: 2px solid #e9ecef;">
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-envelope-fill text-primary me-2"></i>Alamat Email
                  </label>
                  <input type="email" name="email" class="form-control form-control-lg rounded-3" value="{{ $user->email }}" readonly style="background-color: #f8f9fa; border: 2px solid #e9ecef;">
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-house-fill text-primary me-2"></i>Alamat Rumah
                  </label>
                  <input type="text" name="alamat" class="form-control form-control-lg rounded-3" value="{{ $user->alamat }}" readonly style="background-color: #f8f9fa; border: 2px solid #e9ecef;">
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-phone-fill text-primary me-2"></i>Nomor Ponsel
                  </label>
                  <input type="text" name="no_hp" class="form-control form-control-lg rounded-3" value="{{ $user->no_hp }}" readonly style="background-color: #f8f9fa; border: 2px solid #e9ecef;">
                </div>
              </div>
            </div>
          </div>

          {{-- Langkah 2: Data Kamar --}}
          <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-4">
              <div class="d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                  <i class="bi bi-building text-success fs-5"></i>
                </div>
                <div>
                  <h5 class="fw-bold mb-1">Langkah 2: Detail Kamar</h5>
                  <p class="text-muted mb-0 small">Pilih tanggal dan waktu menginap Anda</p>
                </div>
              </div>
            </div>

            <div class="card-body p-4">
              {{-- Room Type Badge --}}
              <div class="mb-4">
                <div class="d-inline-flex align-items-center gap-2 bg-primary bg-opacity-10 px-4 py-3 rounded-pill">
                  <i class="bi bi-door-open-fill text-primary"></i>
                  <span class="fw-bold text-primary">Kamar {{ $kamar->tipeKamar->nama_tipe }}</span>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-box-arrow-in-right text-success me-2"></i>Tanggal Check-in 
                  </label>
                  <input type="date" 
                         name="tanggal_checkin" 
                         class="form-control form-control-lg rounded-3 @error('tanggal_checkin') is-invalid @enderror" 
                         value="{{ old('tanggal_checkin', $tanggalCheckin) }}"
                         min="{{ date('Y-m-d') }}"
                         style="border: 2px solid #e9ecef;"
                         required>
                  @error('tanggal_checkin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-clock-fill text-success me-2"></i>Jam Check-in 
                  </label>
                  <input type="time" 
                         name="jam_checkin" 
                         class="form-control form-control-lg rounded-3 @error('jam_checkin') is-invalid @enderror" 
                         value="{{ old('jam_checkin', '14:00') }}"
                         style="border: 2px solid #e9ecef;"
                         required>
                  @error('jam_checkin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>Waktu check-in standar: 14:00
                  </small>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-box-arrow-right text-danger me-2"></i>Tanggal Check-out 
                  </label>
                  <input type="date" 
                         name="tanggal_checkout" 
                         class="form-control form-control-lg rounded-3 @error('tanggal_checkout') is-invalid @enderror" 
                         value="{{ old('tanggal_checkout', $tanggalCheckout) }}"
                         min="{{ $tanggalCheckin ?? date('Y-m-d', strtotime('+1 day')) }}"
                         style="border: 2px solid #e9ecef;"
                         required>
                  @error('tanggal_checkout')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold text-dark">
                    <i class="bi bi-clock-fill text-danger me-2"></i>Jam Check-out 
                  </label>
                  <input type="time" 
                         name="jam_checkout" 
                         class="form-control form-control-lg rounded-3 @error('jam_checkout') is-invalid @enderror" 
                         value="{{ old('jam_checkout', '12:00') }}"
                         style="border: 2px solid #e9ecef;"
                         required>
                  @error('jam_checkout')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>Waktu check-out standar: 12:00
                  </small>
                </div>
              </div>

              {{-- Price Display --}}
              <div class="mt-4 p-4 bg-light rounded-3 border border-2 border-primary border-opacity-25">
                <div class="d-flex align-items-center justify-content-between">
                  <span class="text-muted">Harga per malam</span>
                  <span class="fw-bold fs-4 text-primary">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>

          {{-- Extra Bed Option --}}
          <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-body p-4">
              <div class="form-check p-0">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border border-2" style="cursor: pointer; transition: all 0.3s ease;">
                  <input class="form-check-input m-0" 
                         type="checkbox" 
                         name="kasur_tambahan" 
                         id="kasur_tambahan" 
                         value="1"
                         style="width: 24px; height: 24px; cursor: pointer;"
                         {{ old('kasur_tambahan') ? 'checked' : '' }}>
                  <label class="form-check-label flex-grow-1 m-0" for="kasur_tambahan" style="cursor: pointer;">
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                        <span class="fw-bold text-dark d-block">Tambah Kasur Ekstra</span>
                        <small class="text-muted">Maksimal 1 kasur tambahan per kamar</small>
                      </div>
                      <span class="badge bg-warning text-dark px-3 py-2">+Rp 100.000</span>
                    </div>
                  </label>
                </div>
              </div>
            </div>
          </div>
          
          {{-- Submit Button --}}
          <div class="d-grid gap-3 mb-4">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
              <i class="bi bi-credit-card me-2"></i>Lanjutkan ke Pembayaran
            </button>
          </div>
        </form>

        {{-- Kebijakan Pembatalan --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
          <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
              <div class="bg-warning bg-opacity-25 rounded-circle p-3 flex-shrink-0">
                <i class="bi bi-shield-fill-check text-warning fs-4"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-3 text-dark">Kebijakan Pembatalan</h6>
                <ul class="mb-3 text-dark small" style="line-height: 1.8;">
                  <li>Check-in pukul <strong>14:00</strong> & Check-out pukul <strong>12:00</strong></li>
                  <li>Pembatalan gratis hingga <strong>H-1</strong> sebelum check-in</li>
                  <li>Kami pastikan uang kembali <strong>100%</strong> jika melakukan pembatalan sesuai kebijakan</li>
                </ul>
                <div class="alert alert-danger border-0 mb-0 py-2 px-3 rounded-3" style="background-color: rgba(220, 38, 38, 0.1);">
                  <small class="text-danger mb-0 d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Tidak ada pengembalian pembayaran jika Anda tidak datang atau check-out lebih awal
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Custom Styles --}}
<style>
  /* Form Control Focus */
  .form-control:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
  }

  /* Checkbox Hover */
  .form-check:hover .border {
    border-color: #667eea !important;
    background-color: rgba(102, 126, 234, 0.05);
  }

  /* Button Hover */
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3) !important;
  }

  /* Card Hover */
  .card {
    transition: all 0.3s ease;
  }

  /* Smooth Animations */
  * {
    transition: all 0.3s ease;
  }
</style>

@endsection
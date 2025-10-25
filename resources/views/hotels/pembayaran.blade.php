@extends('layouts.app')

@section('title', 'Form Pembayaran')

@section('content')
<div class="min-vh-100 py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8" style="max-width: 650px;">
        
        {{-- Header --}}
        <div class="text-center mb-5">
          <div class="d-inline-flex align-items-center gap-3 mb-3">
            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
              <i class="bi bi-credit-card-fill text-primary fs-3"></i>
            </div>
            <div class="text-start">
              <h2 class="fw-bold mb-0">Form Pembayaran</h2>
              <p class="text-muted mb-0 small">Lengkapi informasi pembayaran Anda</p>
            </div>
          </div>
        </div>

        {{-- Total Pembayaran Card --}}
        @if(isset($reservasi))
          <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-4">
              <div class="d-flex align-items-center justify-content-between">
                <div class="text-white">
                  <p class="mb-1 opacity-75 small">Total Pembayaran</p>
                  <h2 class="fw-bold mb-0">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</h2>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                  <i class="bi bi-cash-stack text-white fs-3"></i>
                </div>
              </div>
            </div>
          </div>
        @endif

        {{-- Payment Form Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-success bg-opacity-10 rounded-circle p-3">
                <i class="bi bi-bank text-success fs-5"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1">Informasi Transfer Bank</h5>
                <p class="text-muted mb-0 small">Masukkan detail rekening Anda</p>
              </div>
            </div>
          </div>

          <div class="card-body p-4">
            <form method="POST" action="{{ route('hotel.prosesPembayaran', ['id' => $kamar->id]) }}">
              @csrf

              {{-- Atas Nama --}}
              <div class="mb-4">
                <label class="form-label fw-semibold text-dark mb-2">
                  <i class="bi bi-person-badge-fill text-primary me-2"></i>Atas Nama Rekening
                  <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="atas_nama" 
                       class="form-control form-control-lg rounded-3" 
                       style="border: 2px solid #e9ecef;"
                       required>
                <small class="text-muted">
                  <i class="bi bi-info-circle me-1"></i>Nama sesuai dengan rekening bank Anda
                </small>
              </div>

              {{-- Pilih Bank --}}
              <div class="mb-4">
                <label class="form-label fw-semibold text-dark mb-2">
                  <i class="bi bi-bank2 text-primary me-2"></i>Bank Tujuan
                  <span class="text-danger">*</span>
                </label>
                <select name="bank" class="form-select form-select-lg rounded-3" style="border: 2px solid #e9ecef;" required>
                  <option value="">-- Pilih Bank --</option>
                  <option value="mandiri">
                    <i class="bi bi-bank"></i> Bank Mandiri
                  </option>
                  <option value="bca">Bank BCA</option>
                  <option value="bri">Bank BRI</option>
                  <option value="bni">Bank BNI</option>
                </select>
                <small class="text-muted">
                  <i class="bi bi-info-circle me-1"></i>Pilih bank yang akan Anda gunakan untuk transfer
                </small>
              </div>

              {{-- Nomor Rekening Pengirim --}}
              <div class="mb-4">
                <label class="form-label fw-semibold text-dark mb-2">
                  <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>Nomor Rekening Pengirim
                  <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="nomor_rekening" 
                       class="form-control form-control-lg rounded-3" 
                       maxlength="20"
                       style="border: 2px solid #e9ecef;"
                       required>
                <small class="text-muted">
                  <i class="bi bi-info-circle me-1"></i>Masukkan nomor rekening Anda tanpa spasi
                </small>
              </div>

              {{-- Hidden field kamar --}}
              <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">

              {{-- Submit Button --}}
              <div class="d-grid gap-3 mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                  <i class="bi bi-check-circle-fill me-2"></i>Konfirmasi & Pesan Sekarang
                </button>
              </div>
            </form>
          </div>
        </div>

        {{-- Info Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
          <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
              <div class="bg-info bg-opacity-25 rounded-circle p-3 flex-shrink-0">
                <i class="bi bi-shield-check text-info fs-4"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-3 text-dark">Informasi Penting</h6>
                <ul class="mb-0 text-dark small" style="line-height: 1.8;">
                  <li>Pastikan data rekening yang Anda masukkan <strong>benar dan sesuai</strong></li>
                  <li>Simpan bukti transfer Anda untuk keperluan konfirmasi</li>
                </ul>
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
  .form-control:focus,
  .form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
  }

  /* Button Hover */
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3) !important;
  }

  /* Input Placeholder */
  .form-control::placeholder,
  .form-select::placeholder {
    color: #cbd5e1;
  }

  /* Smooth Transitions */
  * {
    transition: all 0.3s ease;
  }

  /* Card Hover */
  .card {
    transition: all 0.3s ease;
  }
</style>

@endsection
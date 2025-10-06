<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

@php
  // Ubah bahasa Carbon jadi Bahasa Indonesia
  \Carbon\Carbon::setLocale('id');
@endphp

<div class="container py-5 d-flex justify-content-center">
  <div class="card shadow-sm p-4" style="max-width: 480px; border: 1.5px solid #ddd; border-radius: 12px;">
    <h5 class="fw-bold text-center mb-4">Invoice Pemesanan</h5>

    <div class="border p-3 rounded mb-3">
      <div class="d-flex justify-content-between">
        <p class="mb-1 fw-semibold">Tanggal Check-in</p>
        <div class="text-end">
          <p class="mb-0 fw-semibold">
            {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->translatedFormat('l, d F Y') }}
          </p>
          <small>({{ $reservasi->jam_checkin }})</small>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-2">
        <p class="mb-1 fw-semibold">Tanggal Check-out</p>
        <div class="text-end">
          <p class="mb-0 fw-semibold">
            {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->translatedFormat('l, d F Y') }}
          </p>
          <small>({{ $reservasi->jam_checkout }})</small>
        </div>
      </div>

      @php
        $malam = \Carbon\Carbon::parse($reservasi->tanggal_checkin)
                    ->diffInDays(\Carbon\Carbon::parse($reservasi->tanggal_checkout));
        $hargaPerMalam = $reservasi->kamar->harga;
        $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;
        $totalBayar = ($hargaPerMalam * $malam) + $hargaKasur;
      @endphp

      <div class="text-center mt-3">
        <span class="badge bg-light text-dark border">{{ $malam }} malam, 1 kamar</span>
      </div>
    </div>

    <h6 class="fw-semibold mb-2">Kamar {{ $reservasi->kamar->tipeKamar->nama_tipe }}</h6>
    <hr>

    @for ($i = 0; $i < $malam; $i++)
      @php
        $tanggal = \Carbon\Carbon::parse($reservasi->tanggal_checkin)->addDays($i);
      @endphp
      <div class="d-flex justify-content-between">
        <p class="mb-1">{{ $tanggal->translatedFormat('l, d F Y') }}</p>
        <p class="mb-1">Rp{{ number_format($reservasi->kamar->harga, 0, ',', '.') }}</p>
      </div>
    @endfor

    @if ($reservasi->kasur_tambahan)
      <hr>
      <div class="d-flex justify-content-between">
        <p class="mb-1 fw-semibold">Kasur tambahan</p>
        <p class="mb-1">Rp{{ number_format($hargaKasur, 0, ',', '.') }}</p>
      </div>
    @endif

    <hr>
    <div class="d-flex justify-content-between fw-bold">
      <p class="mb-0">Total Bayar</p>
      <p class="mb-0 text-dark">Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
    </div>
    
    <p class="text-center text-muted small mt-2">
      Kami akan mengonfirmasi pesanan Anda dan pembayaran akan diproses hari ini.
    </p>

    <div class="d-grid gap-2 mt-3">
      <a href="{{ route('invoice.download', $reservasi->id) }}" 
        class="btn btn-outline-secondary rounded-pill py-2">
        Unduh PDF
      </a>

      {{-- Tombol kembali ke beranda --}}
      <a href="{{ route('home') }}" 
        class="btn btn-success rounded-pill py-2">
        Kembali ke Beranda
      </a>
    </div>
  </div>
</div>

</body>
</html>

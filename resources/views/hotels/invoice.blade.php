<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pemesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

@php
  \Carbon\Carbon::setLocale('id');
  $malam = \Carbon\Carbon::parse($reservasi->tanggal_checkin)
              ->diffInDays(\Carbon\Carbon::parse($reservasi->tanggal_checkout));
  $hargaPerMalam = $reservasi->kamar->harga;
  $hargaKasur = $reservasi->kasur_tambahan ? 100000 : 0;
  $subtotal = ($hargaPerMalam * $malam) + $hargaKasur;
  
  // Ambil info diskon dari database pembayaran (jika kolom ada) atau session
  $discountCode = null;
  $discountAmount = 0;
  $discountName = null;
  
  // Coba ambil dari database dulu (jika migration sudah dijalankan)
  if ($reservasi->pembayaran && isset($reservasi->pembayaran->discount_code)) {
      $discountCode = $reservasi->pembayaran->discount_code;
      $discountAmount = $reservasi->pembayaran->discount_amount ?? 0;
  } else {
      // Fallback ke session persistent
      $discountInfo = session('discount_info_' . $reservasi->id);
      if ($discountInfo) {
          $discountCode = $discountInfo['code'];
          $discountAmount = $discountInfo['amount'];
          $discountName = $discountInfo['name'];
      }
  }
  
  // Ambil nama diskon berdasarkan kode (jika belum ada dari session)
  if ($discountCode && !$discountName) {
      $discountCodes = [
          'WELCOME10' => 'Welcome Discount',
          'WEEKEND20' => 'Weekend Special', 
          'SAVE50K' => 'Fixed Discount'
      ];
      $discountName = $discountCodes[$discountCode] ?? 'Diskon Khusus';
  }
  
  $totalBayar = $subtotal - $discountAmount;
@endphp

<div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-md border border-gray-200">
    <h2 class="text-xl font-semibold text-center mb-5 text-gray-800">Invoice Pemesanan</h2>
    <p class="text-gray-700 mb-2">
      Kode Invoice:
    <span class="font-semibold text-blue-600">{{ $kode_unik ?? 'Belum ada' }}</span>
  </p>

    {{-- Informasi Pembayaran --}}
    @if ($reservasi->pembayaran)
        <div class="border border-gray-200 rounded-lg p-3 mb-4 bg-gray-50">
            <p class="font-medium text-gray-800">Atas Nama: <span class="font-normal">{{ $reservasi->pembayaran->atas_nama }}</span></p>
            <p class="font-medium text-gray-800">Bank: <span class="font-normal uppercase">{{ $reservasi->pembayaran->bank }}</span></p>
            <p class="font-medium text-gray-800">No. Rekening: <span class="font-normal">{{ $reservasi->pembayaran->nomor_rekening }}</span></p>
            {{-- Informasi transfer ke hotel (tujuan) --}}
            <hr class="my-3">
            <p class="font-medium text-gray-800">Transfer ke Hotel: <span class="font-normal">{{ $reservasi->kamar->hotel->nama_hotel ?? '-' }}</span></p>
            <p class="font-medium text-gray-800">Bank Hotel: <span class="font-normal">{{ $reservasi->kamar->hotel->bank ?? '-' }}</span></p>
            <p class="font-medium text-gray-800">No. Rekening Hotel: <span class="font-normal">{{ $reservasi->kamar->hotel->norek ?? '-' }}</span></p>
        </div>
    @else
        <div class="border border-gray-200 rounded-lg p-3 mb-4 text-gray-500 text-center bg-gray-50">
            Belum ada data pembayaran.
        </div>
    @endif

    {{-- Detail Tanggal --}}
    <div class="border border-gray-200 rounded-lg p-3 mb-4 bg-gray-50">
        <div class="flex justify-between mb-2">
            <p class="font-medium text-gray-700">Check-in</p>
            <div class="text-right">
                <p class="text-gray-800">{{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->translatedFormat('l, d F Y') }}</p>
                <p class="text-sm text-gray-500">{{ $reservasi->jam_checkin }}</p>
            </div>
        </div>
        <div class="flex justify-between">
            <p class="font-medium text-gray-700">Check-out</p>
            <div class="text-right">
                <p class="text-gray-800">{{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->translatedFormat('l, d F Y') }}</p>
                <p class="text-sm text-gray-500">{{ $reservasi->jam_checkout }}</p>
            </div>
        </div>

        <div class="text-center mt-3">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                {{ $malam }} malam, 1 kamar
            </span>
        </div>
    </div>

    {{-- Detail Kamar & Harga --}}
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">
           <p>Kamar {{ $reservasi->kamar->tipeKamar->nama_tipe }}</p>
        </h3>

    {{-- 🏷️ Tambahin nomor kamar di sini --}}
    <div class="flex justify-between text-gray-700 mb-1">
        <p>Nomor Kamar</p>
        <p>{{ $reservasi->kamar->nomor_kamar ?? '-' }}</p>
    </div>

        <div class="flex justify-between text-gray-700">
            <p>Harga per malam</p>
            <p>Rp{{ number_format($hargaPerMalam, 0, ',', '.') }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mt-1">
            <p>Lama menginap</p>
            <p>{{ $malam }} malam</p>
        </div>

        @if ($reservasi->kasur_tambahan)
            <div class="flex justify-between text-gray-700 mt-1">
                <p>Kasur Tambahan</p>
                <p>Rp{{ number_format($hargaKasur, 0, ',', '.') }}</p>
            </div>
        @endif

        <hr class="my-3">
        
        <div class="flex justify-between text-gray-700">
            <p>Subtotal</p>
            <p>Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
        </div>
        
        @if($discountAmount > 0)
            <div class="flex justify-between text-green-600 mt-1">
                <p>Diskon ({{ $discountCode }})</p>
                <p>-Rp{{ number_format($discountAmount, 0, ',', '.') }}</p>
            </div>
            @if($discountName)
                <div class="text-center mt-1">
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                        {{ $discountName }}
                    </span>
                </div>
            @endif
        @endif

        <hr class="my-3">

        <div class="flex justify-between font-semibold text-gray-900 text-lg">
            <p>Total Bayar</p>
            <p>Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <p class="text-center text-gray-500 text-sm mb-3">
        Terima kasih.<br>Tunjukkan invoice ini saat check-in di hotel.
    </p>

    <div class="space-y-2">
        <a href="{{ route('invoice.download', $reservasi->id) }}"
           class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-full py-2 transition">
           Unduh PDF
        </a>

        <a href="{{ route('home') }}"
           class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-full py-2 transition">
           Kembali ke Beranda
        </a>
    </div>
</div>

</body>
</html>

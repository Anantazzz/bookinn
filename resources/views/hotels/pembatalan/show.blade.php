<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembatalan Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

@php
    \Carbon\Carbon::setLocale('id');
@endphp

<div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-md border border-gray-200">
    <h2 class="text-xl font-semibold text-center mb-5 text-gray-800">Struk Pembatalan Reservasi</h2>

    {{-- Detail Pembatalan --}}
     <div class="border border-gray-200 rounded-lg p-3 mb-4 bg-gray-50">
        <div class="flex justify-between text-gray-700 mb-1">
            <p>Hotel :</p>
            <p class="font-medium">{{ $pembatalan->reservasi->kamar->hotel->nama_hotel ?? '-' }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mb-1">
            <p>Kode Reservasi :</p>
            <p class="font-medium">{{ $pembatalan->reservasi->id }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mb-1">
            <p>Nama Tamu :</p>
            <p class="font-medium">{{ $pembatalan->reservasi->nama_tamu ?? '-' }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mb-1">
            <p>Tipe Kamar :</p>
            <p class="font-medium">{{ $pembatalan->reservasi->kamar->tipeKamar->nama_tipe ?? '-' }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mb-1">
            <p>Tanggal Check-In :</p>
            <p>{{ \Carbon\Carbon::parse($pembatalan->reservasi->tanggal_checkin)->translatedFormat('d M Y') }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mb-1">
            <p>Tanggal Check-Out :</p>
            <p>{{ \Carbon\Carbon::parse($pembatalan->reservasi->tanggal_checkout)->translatedFormat('d M Y') }}</p>
        </div>

        <div class="flex justify-between text-gray-700 mb-1">
            <p>Jumlah Refund :</p>
            <p class="font-semibold text-green-600">
                Rp{{ number_format($pembatalan->jumlah_refund, 0, ',', '.') }}
            </p>
        </div>

        <div class="mt-3 text-gray-700">
            <p class="font-medium mb-1">Alasan Pembatalan :</p>
            <p class="text-sm bg-white p-2 rounded-md border border-gray-200">{{ $pembatalan->alasan ?? '-' }}</p>
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-gray-500 text-sm mb-3">
        Terima kasih telah menggunakan layanan kami.
    </p>
        <a href="{{ route('riwayat') }}"
            class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-full py-2 transition">
            Kembali ke Riwayat
        </a>
    </div>
</div>

</body>
</html>

@extends('layouts.resep')

@section('content')
@php
  \Carbon\Carbon::setLocale('id');
  $totalBenar = $reservasi->pembayaran->jumlah_bayar;
@endphp

<div class="min-h-screen flex items-center justify-center bg-gray-100 py-8">
    <div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-md border border-gray-200">
        <h2 class="text-xl font-semibold text-center mb-5 text-gray-800">Invoice Pemesanan Offline</h2>
        <p class="text-gray-700 mb-2">
            Kode Invoice:
            <span class="font-semibold text-blue-600">{{ $invoice->kode_unik ?? 'INV-OFFLINE' }}</span>
        </p>

        {{-- Informasi Tamu --}}
        <div class="border border-gray-200 rounded-lg p-3 mb-4 bg-gray-50">
            <p class="font-medium text-gray-800">Nama Tamu: <span class="font-normal">{{ $tamuOffline->nama ?? '-' }}</span></p>
            <p class="font-medium text-gray-800">No. HP: <span class="font-normal">{{ $tamuOffline->no_hp ?? '-' }}</span></p>
            <p class="font-medium text-gray-800">Metode Bayar: <span class="font-normal">Tunai</span></p>
        </div>

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
                    {{ $jumlahHari }} malam, 1 kamar
                </span>
            </div>
        </div>

        {{-- Detail Kamar & Harga --}}
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                <p>Kamar {{ $tipeKamar->nama_tipe }}</p>
            </h3>

            <div class="flex justify-between text-gray-700 mb-1">
                <p>Nomor Kamar</p>
                <p>{{ $reservasi->kamar->nomor_kamar ?? '-' }}</p>
            </div>

            <div class="flex justify-between text-gray-700">
                <p>Harga per malam</p>
                <p>Rp{{ number_format($reservasi->kamar->harga, 0, ',', '.') }}</p>
            </div>

            <div class="flex justify-between text-gray-700 mt-1">
                <p>Lama menginap</p>
                <p>{{ $jumlahHari }} malam</p>
            </div>

            @if ($reservasi->kasur_tambahan)
                <div class="flex justify-between text-gray-700 mt-1">
                    <p>Kasur Tambahan</p>
                    <p>Rp{{ number_format($kasurTambahan, 0, ',', '.') }}</p>
                </div>
            @endif
            
            @php
                $subtotal = ($reservasi->kamar->harga * $jumlahHari) + ($reservasi->kasur_tambahan ? $kasurTambahan : 0);
                $hasDiscount = $reservasi->pembayaran->discount_code && $reservasi->pembayaran->discount_amount > 0;
            @endphp
            
            @if($hasDiscount)
                <div class="flex justify-between text-gray-700 mt-1">
                    <p>Subtotal</p>
                    <p>Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                </div>
                <div class="flex justify-between text-green-600 mt-1">
                    <p>Diskon ({{ $reservasi->pembayaran->discount_code }})</p>
                    <p>-Rp{{ number_format($reservasi->pembayaran->discount_amount, 0, ',', '.') }}</p>
                </div>
            @endif

            <hr class="my-3">

            <div class="flex justify-between font-semibold text-gray-900">
                <p>Total Bayar</p>
                <p>Rp{{ number_format($totalBenar, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <p class="text-center text-gray-500 text-sm mb-3">
            Terima kasih.<br>Reservasi offline berhasil dibuat.
        </p>

        <div class="space-y-2">
            <a href="{{ route('resepsionis.dashboard') }}"
               class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-full py-2 transition">
               Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-10">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Detail Kamar</h2>

    {{-- Gambar Kamar --}}
    @if($kamar->gambar)
        <div class="mb-6">
            <img src="{{ asset('images/kamars/' . $kamar->gambar) }}" 
                 alt="Gambar Kamar" 
                 class="rounded-lg w-full max-h-96 object-cover shadow-sm">
        </div>
    @endif

    {{-- Informasi Kamar --}}
    <div class="space-y-2 text-gray-700">
        <p><strong>Nama Hotel:</strong> {{ $kamar->hotel->nama_hotel }}</p>
        <p><strong>Tipe Kamar:</strong> {{ $kamar->tipeKamar->nama_tipe ?? '-' }}</p>
        <p><strong>Nomor Kamar:</strong> {{ $kamar->nomor_kamar }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($kamar->harga, 0, ',', '.') }}</p>
        <p><strong>Kapasitas:</strong> {{ $kamar->kapasitas }} orang</p>
        <p><strong>Jumlah Bed:</strong> {{ $kamar->jumlah_bed }}</p>
        <p><strong>Internet:</strong> {{ $kamar->internet ? 'Tersedia' : 'Tidak Ada' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($kamar->status) }}</p>
    </div>

    {{-- Tombol aksi --}}
    <div class="mt-6 flex gap-2">
        <a href="{{ route('admin.kamars.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
           Kembali
        </a>
        <a href="{{ route('admin.kamars.edit', $kamar->id) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
           Edit
        </a>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <div class="flex-1 p-10">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Edit Data Kamar</h1>

        {{-- Notifikasi sukses/error --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit Data Kamar --}}
        <form action="{{ route('admin.kamars.update', $kamar->id) }}" method="POST" enctype="multipart/form-data"
              class="bg-white shadow-md rounded-xl p-10 border border-gray-400 max-w-3xl mx-auto">
            @csrf
            @method('PUT')

            {{-- Pilih Hotel --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nama Hotel</label>
                <select name="hotel_id"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2" required>
                    <option value="">-- Pilih Hotel --</option>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ $hotel->id == $kamar->hotel_id ? 'selected' : '' }}>
                            {{ $hotel->nama_hotel }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipe Kamar --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Tipe Kamar</label>
                <select name="tipe_kamar_id"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2" required>
                    <option value="">-- Pilih Tipe Kamar --</option>
                    @foreach ($tipeKamars as $tipe)
                        <option value="{{ $tipe->id }}" {{ $tipe->id == $kamar->tipe_kamar_id ? 'selected' : '' }}>
                            {{ $tipe->nama_tipe }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nomor Kamar --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nomor Kamar</label>
                <input type="text" name="nomor_kamar" value="{{ $kamar->nomor_kamar }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
            </div>

            {{-- Harga --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ $kamar->harga }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
            </div>

            {{-- Kapasitas --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Kapasitas (Orang)</label>
                <input type="number" name="kapasitas" value="{{ $kamar->kapasitas }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
            </div>

            {{-- Jumlah Bed --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Jumlah Bed</label>
                <input type="number" name="jumlah_bed" value="{{ $kamar->jumlah_bed }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
            </div>

            {{-- Internet --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Fasilitas Internet</label>
                <select name="internet"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
                    <option value="1" {{ $kamar->internet ? 'selected' : '' }}>Ada</option>
                    <option value="0" {{ !$kamar->internet ? 'selected' : '' }}>Tidak Ada</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Status Kamar</label>
                <select name="status"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
                    <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="booking" {{ $kamar->status == 'booking' ? 'selected' : '' }}>Booking</option>
                </select>
            </div>

            {{-- Gambar --}}
            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-2">Upload Gambar Kamar</label>
                @if ($kamar->gambar)
                    <img src="{{ asset('images/kamars/' . $kamar->gambar) }}"
                         alt="Gambar Kamar"
                         class="w-48 h-32 object-cover mb-3 rounded-lg">
                @endif
                <input type="file" name="gambar"
                       class="w-full border border-gray-500 rounded-lg py-2 px-3 focus:ring-blue-500 focus:border-blue-600"
                       accept="image/*">
                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, WEBP | Maks: 2MB</p>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.kamars.index') }}"
                   class="bg-gray-400 text-white px-5 py-2 rounded-lg hover:bg-gray-500 transition">Kembali</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

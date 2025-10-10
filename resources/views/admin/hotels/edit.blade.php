@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <div class="flex-1 p-10">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Edit Data Hotel</h1>

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

        {{-- Form Edit Data Hotel --}}
        <form action="{{ route('admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data"
              class="bg-white shadow-md rounded-xl p-10 border border-gray-400 max-w-3xl mx-auto">
            @csrf
            @method('PUT')

            {{-- Nama Hotel --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nama Hotel</label>
                <input type="text" name="nama_hotel" value="{{ old('nama_hotel', $hotel->nama_hotel) }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                       required>
            </div>

            {{-- Kota --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $hotel->kota) }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                       required>
            </div>

            {{-- Alamat --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                <textarea name="alamat" rows="3"
                          class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                          required>{{ old('alamat', $hotel->alamat) }}</textarea>
            </div>

            {{-- Bintang --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Bintang</label>
                <input type="number" name="bintang" min="1" max="5" value="{{ old('bintang', $hotel->bintang) }}"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                       required>
            </div>

            {{-- Gambar --}}
            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-2">Upload Gambar Hotel</label>
                @if ($hotel->gambar)
                    <img src="{{ asset('images/' . $hotel->gambar) }}"
                         alt="{{ $hotel->nama_hotel }}"
                         class="w-48 h-32 object-cover mb-3 rounded-lg">
                @endif
                <input type="file" name="gambar"
                       class="w-full border border-gray-500 rounded-lg py-2 px-3 focus:ring-blue-500 focus:border-blue-600"
                       accept="image/*">
                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, WEBP | Maks: 2MB</p>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.hotels.index') }}"
                   class="bg-gray-400 text-white px-5 py-2 rounded-lg hover:bg-gray-500 transition">Kembali</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

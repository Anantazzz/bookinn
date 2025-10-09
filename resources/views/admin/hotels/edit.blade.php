@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-6">Edit Data Hotel</h2>

    <form action="{{ route('admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium mb-1">Nama Hotel</label>
            <input type="text" name="nama_hotel" value="{{ old('nama_hotel', $hotel->nama_hotel) }}" class="w-full border-gray-300 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Kota</label>
            <input type="text" name="kota" value="{{ old('kota', $hotel->kota) }}" class="w-full border-gray-300 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Alamat</label>
            <textarea name="alamat" rows="3" class="w-full border-gray-300 rounded-lg">{{ old('alamat', $hotel->alamat) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Bintang</label>
            <input type="number" name="bintang" min="1" max="5" value="{{ old('bintang', $hotel->bintang) }}" class="w-full border-gray-300 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Gambar Hotel</label>
            @if ($hotel->gambar)
                <img src="{{ asset('images/' . $hotel->gambar) }}" alt="{{ $hotel->nama_hotel }}" class="rounded-lg w-full max-h-96 object-cover">
            @endif
            <input type="file" name="gambar" class="w-full border-gray-300 rounded-lg">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.hotels.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection

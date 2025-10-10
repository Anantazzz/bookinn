@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Detail Hotel</h2>

    <div class="mb-4">
         <img src="{{ asset('images/' . $hotel->gambar) }}" alt="{{ $hotel->nama_hotel }}" class="rounded-lg w-full max-h-96 object-cover">
    </div>

    <div class="space-y-2">
        <p><strong>Nama Hotel:</strong> {{ $hotel->nama_hotel }}</p>
        <p><strong>Kota:</strong> {{ $hotel->kota }}</p>
        <p><strong>Alamat:</strong> {{ $hotel->alamat }}</p>
        <p><strong>Bintang:</strong> {{ $hotel->bintang }} ⭐</p>
    </div>

     {{-- Tombol aksi --}}
      <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('admin.hotels.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
           Kembali
        </a>

        <a href="{{ route('admin.hotels.edit', $hotel->id) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
           Edit
        </a>
    </div>
</div>
@endsection

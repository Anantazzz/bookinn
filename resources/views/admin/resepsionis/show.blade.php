@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-2xl p-6 mt-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Detail Resepsionis</h2>

    <div class="space-y-3 text-gray-700">
        <p><strong>Nama:</strong> {{ $resepsionis->name }}</p>
        <p><strong>Email:</strong> {{ $resepsionis->email }}</p>
        <p><strong>No HP:</strong> {{ $resepsionis->no_hp ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $resepsionis->alamat ?? '-' }}</p>
        <p><strong>Shift:</strong> {{ $resepsionis->shift ?? 'Pagi' }}</p>
        <p><strong>Hotel:</strong> {{ $resepsionis->nama_hotel ?? 'Hotel ABC' }}</p>
        <p><strong>Role:</strong> {{ ucfirst($resepsionis->role) }}</p>
    </div>

     {{-- Tombol aksi --}}
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('admin.resepsionis.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
           Kembali
        </a>

        <a href="{{ route('admin.resepsionis.edit', $resepsionis->id) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
           Edit
        </a>
    </div>
</div>
@endsection

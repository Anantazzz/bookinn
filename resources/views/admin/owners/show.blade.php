@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mt-10">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800 text-center">Detail Owner</h2>

    {{-- Foto Owner --}}
    @if($owner->foto)
        <div class="mb-6">
            <img src="{{ asset('storage/' . $owner->foto) }}" 
                 alt="Foto Owner" 
                 class="rounded-lg w-full max-h-96 object-cover shadow-sm">
        </div>
    @endif

    {{-- Informasi Owner --}}
    <div class="space-y-2 text-gray-700">
        <p><strong>Nama:</strong> {{ $owner->name }}</p>
        <p><strong>Email:</strong> {{ $owner->email }}</p>
        <p><strong>Nomor HP:</strong> {{ $owner->no_hp ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $owner->alamat ?? '-' }}</p>
        <p><strong>Hotel:</strong> {{ $owner->hotel->nama_hotel ?? '-' }}</p>
        <p><strong>Role:</strong> {{ ucfirst($owner->role) }}</p>
    </div>

    {{-- Tombol aksi --}}
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('admin.owners.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
           Kembali
        </a>

        <a href="{{ route('admin.owners.edit', $owner->id) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
           Edit
        </a>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-gray-900 text-gray-200 rounded-2xl shadow-lg overflow-hidden mt-12 p-8">
    {{-- Judul --}}
    <h2 class="text-3xl font-bold text-center mb-8 tracking-wide">Detail Owner</h2>

    {{-- Informasi Owner --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div>
            <p class="text-sm text-gray-400 uppercase mb-1">Nama</p>
            <p class="text-lg font-semibold">{{ $owner->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-400 uppercase mb-1">Email</p>
            <p class="text-lg font-semibold">{{ $owner->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-400 uppercase mb-1">Nomor HP</p>
            <p class="text-lg font-semibold">{{ $owner->no_hp ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-400 uppercase mb-1">Alamat</p>
            <p class="text-lg font-semibold">{{ $owner->alamat ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-400 uppercase mb-1">Hotel</p>
            <p class="text-lg font-semibold">{{ $owner->hotel->nama_hotel ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-400 uppercase mb-1">Role</p>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                         {{ $owner->role === 'owner' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-200' }}">
                {{ ucfirst($owner->role) }}
            </span>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-800">
        <a href="{{ route('admin.owners.index') }}" 
           class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200">
           ← Kembali
        </a>

        <a href="{{ route('admin.owners.edit', $owner->id) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200">
            Edit
        </a>
    </div>
</div>
@endsection

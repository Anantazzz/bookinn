@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Edit Data Owner</h2>

    <form action="{{ route('admin.owners.update', $owner->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $owner->name) }}"
                class="w-full border border-gray-400 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-600">
        </div>

         {{-- Pilih Hotel --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Hotel yang Dimiliki</label>
            <select name="hotel_id"
                class="w-full border border-gray-400 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-600">
                <option value="">-- Pilih Hotel --</option>
                @foreach ($hotels as $hotel)
                    <option value="{{ $hotel->id }}" {{ $owner->hotel_id == $hotel->id ? 'selected' : '' }}>
                        {{ $hotel->nama_hotel }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $owner->email) }}"
                class="w-full border border-gray-400 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-600">
        </div>

        {{-- No HP --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $owner->no_hp) }}"
                class="w-full border border-gray-400 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-600">
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
            <textarea name="alamat" rows="2"
                class="w-full border border-gray-400 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-600">{{ old('alamat', $owner->alamat) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.owners.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection

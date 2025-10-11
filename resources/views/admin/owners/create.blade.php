@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <div class="flex-1 p-10">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Tambah Data Owner</h1>

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

        {{-- Form Tambah Data Owner --}}
        <form action="{{ route('admin.owners.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white shadow-md rounded-xl p-10 border border-gray-400 max-w-3xl mx-auto">
            @csrf

            {{-- Nama --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="name"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                       required>
            </div>
            
            {{-- Pilih Hotel --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Hotel yang Dimiliki</label>
                <select name="hotel_id"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2" required>
                    <option value="">-- Pilih Hotel --</option>
                    @foreach ($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                       required>
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"
                       required>
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                <input type="text" name="no_hp"
                       class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
            </div>

            {{-- Alamat --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                <textarea name="alamat" rows="3"
                          class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"></textarea>
            </div>

            {{-- Role otomatis --}}
            <input type="hidden" name="role" value="owner">

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.owners.index') }}"
                   class="bg-gray-400 text-white px-5 py-2 rounded-lg hover:bg-gray-500 transition">Batal</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

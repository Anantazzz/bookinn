@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    
    {{-- Main Content --}}
    <div class="flex-1 p-10">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Tambah Data Hotel</h1>

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

        {{-- Form Tambah Data --}}
        <form action="{{ route('admin.hotels.store') }}" method="POST" 
              class="bg-white shadow-md rounded-xl p-10 border border-gray-400 max-w-3xl mx-auto">
            @csrf

            {{-- Nama Hotel --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Nama Hotel</label>
                <input type="text" id="nama_hotel" name="nama_hotel"
                class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2">
            </div>

            {{-- Gambar --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Pilih Gambar Hotel</label>
                <input type="file" name="gambar" 
                    class="w-full border border-gray-500 rounded-lg py-2 px-3 focus:ring-blue-500 focus:border-blue-600">
            </div>

            {{-- Kota --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Kota</label>
                <select id="kota" name="kota"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2" required>
                    <option value="">-- Pilih --</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Bali">Bali</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Lombok">Lombok</option>
                    <option value="Pangandaran">Pangandaran</option>
                    <option value="Malang">Malang</option>
                    <option value="Banda Neira">Banda Neira</option>
                    <option value="Surabaya">Surabaya</option>
                    <option value="Sukabumi">Sukabumi</option>
                    <option value="Papua">Papua</option>
                    <option value="Palembang">Palembang</option>
                </select>
            </div>

            {{-- Alamat --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3"
                class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2"></textarea>
            </div>

            {{-- Bintang --}}
            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-2">Pilih Bintang</label>
                <select id="bintang" name="bintang"
                        class="w-full border border-gray-500 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-600 px-3 py-2" required>
                    <option value="">-- Pilih --</option>
                    <option value="3">3 Bintang</option>
                    <option value="4">4 Bintang</option>
                    <option value="5">5 Bintang</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.hotels.index') }}"
                   class="bg-gray-400 text-white px-5 py-2 rounded-lg hover:bg-gray-500 transition">Batal</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

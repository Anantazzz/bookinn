@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Resepsionis</h1>

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.resepsionis.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700 font-semibold">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Password</label>
            <input type="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Alamat</label>
            <input type="text" name="alamat" value="{{ old('alamat') }}" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Shift</label>
            <select name="shift" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                @foreach ($shiftOptions as $shift)
                    <option value="{{ $shift }}">{{ $shift }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold">Nama Hotel</label>
            <input type="text" name="nama_hotel" value="{{ old('nama_hotel') }}" 
                   placeholder="Ketik nama hotel di sini..."
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.resepsionis.index') }}" 
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-400">Batal</a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection

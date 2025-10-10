@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Hotel</h1>
        <a href="{{ route('admin.hotels.create') }}" 
           class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Hotel
        </a>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
            <tr>
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Gambar</th>
                <th class="py-3 px-4 text-left">Nama Hotel</th>
                <th class="py-3 px-4 text-left">Kota</th>
                <th class="py-3 px-4 text-left">Bintang</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($hotels as $hotel)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $hotel->id }}</td>
                    <td class="px-4 py-2">
                        @if ($hotel->gambar)
                            <img src="{{ asset('images/' . $hotel->gambar) }}" 
                                alt="{{ $hotel->nama_hotel }}" 
                                class="w-20 h-20 object-cover rounded-lg shadow-sm">
                        @else
                            <span class="text-gray-400 italic">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $hotel->nama_hotel }}</td>
                    <td class="py-3 px-4">{{ $hotel->kota }}</td>
                    <td class="py-3 px-4">{{ $hotel->bintang }}</td>
                    
                    {{-- Aksi --}}
                    <td class="py-3 px-4 space-x-2">
                        <a href="{{ route('admin.hotels.show', $hotel->id) }}" 
                           class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">Detail</a>
                        <a href="{{ route('admin.hotels.edit', $hotel->id) }}" 
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" 
                              method="POST" class="inline" 
                              onsubmit="return confirm('Yakin ingin menghapus hotel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                     class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-600 py-4">
                        Belum ada data hotel.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

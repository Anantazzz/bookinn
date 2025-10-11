@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Owner</h1>
        <a href="{{ route('admin.owners.create') }}" 
           class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Owner
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
                <th class="py-3 px-4 text-left">Nama</th>
                <th class="py-3 px-4 text-left">Hotel</th>
                <th class="py-3 px-4 text-left">Email</th>
                <th class="py-3 px-4 text-left">No Hp</th>
                <th class="py-3 px-4 text-left">Alamat</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @forelse ($owners as $index => $owner)
                <tr class="hover:bg-gray-50">
                    {{-- ID --}}
                    <td class="py-3 px-4">{{ $index + 1 }}</td>

                    {{-- Nama --}}
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $owner->name }}</td>

                     {{-- Hotel --}}
                    <td class="py-3 px-4 text-gray-700">{{ $owner->hotel->nama_hotel ?? '-' }}</td>

                    {{-- Email --}}
                    <td class="py-3 px-4 text-gray-700">{{ $owner->email }}</td>

                    {{-- No HP --}}
                    <td class="py-3 px-4 text-gray-700">{{ $owner->no_hp ?? '-' }}</td>

                    {{-- Alamat --}}
                    <td class="py-3 px-4 text-gray-700">{{ $owner->alamat ?? '-' }}</td>

                    {{-- Aksi --}}
                    <td class="py-3 px-4 space-x-2">
                        <a href="{{ route('admin.owners.show', $owner->id) }}" 
                           class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">
                            Detail
                        </a>
                        <a href="{{ route('admin.owners.edit', $owner->id) }}" 
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        <form action="{{ route('admin.owners.destroy', $owner->id) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
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
                    <td colspan="7" class="text-center text-gray-600 py-4">
                        Belum ada data owner.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

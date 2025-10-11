@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Resepsionis</h1>
        <a href="{{ route('admin.resepsionis.create') }}" 
           class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Data
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
            <tr>
                <th class="py-3 px-4 text-left">Nama</th>
                <th class="py-3 px-4 text-left">Email</th>
                <th class="py-3 px-4 text-left">No HP</th>
                <th class="py-3 px-4 text-left">Alamat</th>
                <th class="py-3 px-4 text-left">Shift</th>
                <th class="py-3 px-4 text-left">Hotel</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($resepsionis as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $r->name }}</td>
                    <td class="py-3 px-4">{{ $r->email }}</td>
                    <td class="py-3 px-4">{{ $r->no_hp ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $r->alamat ?? '-' }}</td>
                    <td class="px-4 py-2">
                        @if($r->role === 'resepsionis')
                            {{ $r->shift ?? 'Belum ditentukan' }}
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>
                   <td class="py-3 px-4">{{ $r->hotel->nama_hotel ?? '-' }}</td>

                    <td class="py-3 px-4 space-x-2">
                        <a href="{{ route('admin.resepsionis.show', $r->id) }}" 
                            class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">
                           Detail
                        </a>

                        <a href="{{ route('admin.resepsionis.edit', $r->id) }}" 
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                           Edit
                        </a>

                        <form action="{{ route('admin.resepsionis.destroy', $r->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin mau hapus resepsionis ini?')" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-600">
                        Belum ada data resepsionis.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

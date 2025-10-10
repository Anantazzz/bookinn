@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Kamar</h1>
        <a href="{{ route('admin.kamars.create') }}" 
           class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Kamar
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
                <th class="py-3 px-4 text-left">No</th>
                <th class="py-3 px-4 text-left">Gambar</th>
                <th class="py-3 px-4 text-left">Hotel</th>
                <th class="py-3 px-4 text-left">Tipe Kamar</th>
                <th class="py-3 px-4 text-left">Nomor</th>
                <th class="py-3 px-4 text-left">Harga</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @forelse ($kamars as $index => $kamar)
                <tr class="hover:bg-gray-50">
                    {{-- No --}}
                    <td class="py-3 px-4">{{ $index + 1 }}</td>

                    {{-- Gambar --}}
                    <td class="px-4 py-3">
                        @if ($kamar->gambar)
                            <img src="{{ asset('images/kamars/' . $kamar->gambar) }}" 
                                 alt="Gambar Kamar" 
                                 class="w-20 h-20 object-cover rounded-lg shadow-sm">
                        @else
                            <span class="text-gray-400 italic">Tidak ada gambar</span>
                        @endif
                    </td>

                    {{-- Hotel --}}
                    <td class="py-3 px-4">{{ $kamar->hotel->nama_hotel }}</td>

                    {{-- Tipe kamar --}}
                    <td class="py-3 px-4">{{ $kamar->tipeKamar->nama_tipe ?? '-' }}</td>

                    {{-- Nomor kamar --}}
                    <td class="py-3 px-4">{{ $kamar->nomor_kamar }}</td>

                    {{-- Harga --}}
                    <td class="py-3 px-4">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>

                    {{-- Status --}}
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-white text-sm
                            {{ $kamar->status == 'tersedia' ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ ucfirst($kamar->status) }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="py-3 px-4 space-x-2">
                        <a href="{{ route('admin.kamars.show', $kamar->id) }}" 
                           class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">
                            Detail
                        </a>
                        <a href="{{ route('admin.kamars.edit', $kamar->id) }}" 
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        <form action="{{ route('admin.kamars.destroy', $kamar->id) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
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
                    <td colspan="8" class="text-center text-gray-600 py-4">
                        Belum ada data kamar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

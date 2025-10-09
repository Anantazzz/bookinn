@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <div class="flex-1 p-10">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Kamar</h1>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tombol tambah --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.kamars.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                + Tambah Kamar
            </a>
        </div>

        {{-- Tabel kamar --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-300">
            <table class="w-full border-collapse">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Hotel</th>
                        <th class="px-4 py-3 text-left">Tipe Kamar</th>
                        <th class="px-4 py-3 text-left">Nomor</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Gambar</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kamars as $index => $kamar)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $kamar->hotel->nama_hotel }}</td>
                            <td class="px-4 py-3">{{ $kamar->tipeKamar->nama_tipe ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $kamar->nomor_kamar }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-white 
                                    {{ $kamar->status == 'tersedia' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($kamar->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($kamar->gambar)
                                 <img src="{{ asset('images/' . $kamar->gambar) }}" alt="Gambar Kamar" class="w-32 h-20 object-cover rounded-lg">
                                @else
                                    <span class="text-gray-500 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.kamars.edit', $kamar->id) }}"
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kamars.destroy', $kamar->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-600 py-4">Belum ada data kamar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

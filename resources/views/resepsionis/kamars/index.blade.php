@extends('layouts.resep')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-2xl shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-10">Ketersediaan Kamar</h1>

        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-2 px-4 text-left">No</th>
                    <th class="py-2 px-4 text-left">Nomor Kamar</th>
                    <th class="py-2 px-4 text-left">Tipe Kamar</th>
                    <th class="py-2 px-4 text-left">Harga</th>
                    <th class="py-2 px-4 text-left">Kapasitas</th>
                    <th class="py-2 px-4 text-left">Internet</th>
                    <th class="py-2 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kamars as $index => $kamar)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $kamar->nomor_kamar }}</td>
                        <td>{{ $kamar->tipeKamar->nama_tipe ?? '-' }}</td>
                        <td class="py-2 px-4">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                        <td class="py-2 px-4">{{ $kamar->kapasitas }} orang</td>
                        <td class="py-2 px-4">{{ $kamar->internet ? 'Ya' : 'Tidak' }}</td>
                         <td class="py-2 px-4">
                          <form action="{{ route('resepsionis.kamars.update', $kamar->id) }}" method="POST" class="inline-block">
                             @csrf
                                @method('PUT')
                                <select name="status" 
                                        onchange="this.form.submit(); this.style.backgroundColor = this.value == 'tersedia' ? '#10B981' : '#EF4444';"
                                        class="border px-2 py-1 rounded text-white"
                                        style="background-color: {{ $kamar->status == 'tersedia' ? '#10B981' : '#EF4444' }}">
                                    <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="booking" {{ $kamar->status == 'booking' ? 'selected' : '' }}>Booking</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">Belum ada data kamar</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

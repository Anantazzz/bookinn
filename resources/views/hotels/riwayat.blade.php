@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow-md">
    <h2 class="text-2xl font-semibold text-center mb-6">Riwayat Pemesanan</h2>

    @forelse ($reservasis as $r)
        <div class="border border-gray-200 rounded-xl p-5 mb-5 bg-white shadow-sm hover:shadow-md transition">

            {{-- Header: Hotel & Status --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    {{ $r->kamar->hotel->nama_hotel ?? 'Hotel Tidak Ditemukan' }}
                </h3>

                @php
                    $statusColors = [
                        'pending' => 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300 shadow-sm',
                        'aktif' => 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300 shadow-sm',
                        'selesai' => 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300 shadow-sm',
                        'batal' => 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300 shadow-sm',
                    ];
                @endphp

                <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $statusColors[$r->status] ?? 'bg-gray-100 text-gray-700 border border-gray-300' }}">
                    {{ ucfirst($r->status) }}
                </span>
            </div>

            {{-- Detail Informasi --}}
            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-gray-700 text-sm">
                <p><span class="font-semibold">Check-in:</span> {{ \Carbon\Carbon::parse($r->tanggal_checkin)->format('d M Y H:i') }}</p>
                <p><span class="font-semibold">Check-out:</span> {{ \Carbon\Carbon::parse($r->tanggal_checkout)->format('d M Y H:i') }}</p>
                <p><span class="font-semibold">Kasur Tambahan:</span> {{ $r->kasur_tambahan ? 'Ya' : 'Tidak' }}</p>
                <p><span class="font-semibold">Total Harga:</span> Rp{{ number_format($r->total_harga, 0, ',', '.') }}</p>
                <p><span class="font-semibold">Tipe Kamar:</span> {{ $r->kamar->tipeKamar->nama_tipe ?? '-' }}</p>
                <p><span class="font-semibold">Nomor Kamar:</span> {{ $r->kamar->nomor_kamar ?? '-' }}</p>
            </div>

        </div>
    @empty
        <p class="text-gray-500 text-center">Belum ada riwayat pemesanan.</p>
    @endforelse
</div>
@endsection

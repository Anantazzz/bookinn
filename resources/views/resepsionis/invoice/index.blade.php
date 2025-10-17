@extends('layouts.resep')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-md">
     <h1 class="text-3xl font-bold text-gray-800 text-center mb-10">Kelola & Cetak Tagihan Pelanggan</h1>
     
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <table class="table-auto w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Nama Pelanggan</th>
                <th class="p-3 text-left">Kamar</th>
                <th class="py-3 px-4 text-left">Tipe Kamar</th>
                <th class="p-3 text-left">Tanggal Bayar</th>
                <th class="p-3 text-left">Jumlah Bayar</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayarans as $index => $p)
                <tr class="border-t">
                    <td class="p-3">{{ $index + 1 }}</td>
                    <td class="p-3">{{ $p->reservasi->user->name }}</td>
                    <td class="p-3">{{ $p->reservasi->kamar->nomor_kamar }}</td>
                    <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-gray-100 rounded text-sm">
                                {{ $p->reservasi->kamar->tipeKamar->nama_tipe ?? '-' }}
                            </span>
                        </td>
                    <td class="p-3">{{ $p->tanggal_bayar }}</td>
                    <td class="p-3">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="p-3">
                        @if($p->status_bayar === 'pending')
                            <span class="text-orange-600 font-semibold">Pending</span>
                        @else
                            <span class="text-green-600 font-semibold">Success</span>
                        @endif
                    </td>
                    <td class="p-3 flex gap-2">
                        @if($p->status_bayar === 'pending')
                            <form action="{{ route('resepsionis.invoice.accept', $p->id) }}" method="POST">
                                @csrf
                                <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    Accept
                                </button>
                            </form>
                        @else
                            @php
                                $invoice = $p->invoice()->latest()->first();
                            @endphp
                            @if($invoice)
                                <a href="{{ route('resepsionis.invoice.print', $invoice->id) }}"
                                   class="bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">
                                    Cetak Invoice
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

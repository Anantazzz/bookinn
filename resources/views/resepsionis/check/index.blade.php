@extends('layouts.resep')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-10">
            Check-in / Check-out Hotel {{ Auth::user()->hotel->nama ?? '' }}
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left">Customer</th>
                        <th class="py-3 px-4 text-left">Kamar</th>
                        <th class="py-3 px-4 text-left">Tipe Kamar</th>
                        <th class="py-3 px-4 text-left">Check-in</th>
                        <th class="py-3 px-4 text-left">Check-out</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasis as $res)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $res->user->name }}</td>
                            <td class="py-3 px-4">{{ $res->kamar->nomor_kamar }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-gray-100 rounded text-sm">
                                    {{ $res->kamar->tipeKamar->nama_tipe ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $res->tanggal_checkin }} {{ $res->jam_checkin }}</td>
                            <td class="py-3 px-4">{{ $res->tanggal_checkout }} {{ $res->jam_checkout }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $statusToShow = $res->status_for_display ?? $res->status;
                                    $color = match($statusToShow) {
                                        'pending' => 'bg-yellow-200 text-yellow-800',
                                        'aktif' => 'bg-green-200 text-green-800',
                                        'selesai' => 'bg-blue-200 text-blue-800',
                                        'batal' => 'bg-red-200 text-red-800',
                                        default => 'bg-gray-200 text-gray-800'
                                    };
                                @endphp

                                <span class="px-2 py-1 rounded {{ $color }}">
                                    {{ ucfirst($statusToShow) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                {{-- Tombol Check-in: muncul jika status pending DAN sudah waktunya --}}
                                @if($res->can_checkin ?? false)
                                    <form action="{{ route('resepsionis.check.updateStatus', $res->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="aktif">
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                            Check-in
                                        </button>
                                    </form>

                                {{-- Tombol Check-out: muncul jika status aktif --}}
                                @elseif($res->can_checkout ?? false)
                                    <form action="{{ route('resepsionis.check.updateStatus', $res->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                            Check-out
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
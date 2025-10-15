@extends('layouts.resep')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-10">
            Check-in / Check-out Hotel {{ Auth::user()->hotel->nama ?? '' }}
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left">Customer</th>
                        <th class="py-3 px-4 text-left">Kamar</th>
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
                            <td class="py-3 px-4">{{ $res->kamar->nomor_kamar }} ({{ $res->kamar->tipeKamar->nama ?? '-' }})</td>
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
                              @php
                                    $today = now()->toDateString();
                                @endphp

                                @if($res->status == 'pending' && $res->tanggal_checkin <= $today)
                                    <form action="{{ route('resepsionis.check.updateStatus', $res->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="aktif">
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                            Check-in
                                        </button>
                                    </form>
                                @elseif($res->status == 'aktif' && $res->tanggal_checkout <= $today)
                                    <form action="{{ route('resepsionis.check.updateStatus', $res->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                            Check-out
                                        </button>
                                    </form>
                                @else
                                    -
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

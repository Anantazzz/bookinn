@extends('layouts.resep')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Check-in / Check-out</h1>
                        <p class="text-gray-600 text-sm mt-1">{{ Auth::user()->hotel->nama ?? '' }}</p>
                    </div>
                </div>
            </div>

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Search Bar --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 mb-6">
                <form method="GET" action="{{ route('resepsionis.check.index') }}" class="flex gap-3 items-center">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                            name="search" 
                            placeholder="Cari nama pelanggan..." 
                            value="{{ request('search') }}" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <div class="w-56">
                        <label for="status" class="sr-only">Status</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="all" {{ (isset($statusFilter) && $statusFilter == 'all') ? 'selected' : (!isset($statusFilter) ? 'selected' : '') }}>Semua Status</option>
                            <option value="pending" {{ isset($statusFilter) && $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="aktif" {{ isset($statusFilter) && $statusFilter == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ isset($statusFilter) && $statusFilter == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ isset($statusFilter) && $statusFilter == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md hover:shadow-lg transition-all duration-200">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Kamar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Tipe Kamar</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Check-in</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Check-out</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reservasis as $res)
                                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @php
                                                // Ambil nama dari user login atau tamu offline
                                                $namaCustomer = $res->user->name ?? $res->tamu_offline->nama ?? 'Tamu';
                                                $inisial = strtoupper(substr($namaCustomer, 0, 1));
                                            @endphp

                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                                {{ $inisial }}
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $namaCustomer }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1.5 bg-gradient-to-r from-indigo-100 to-indigo-50 border border-indigo-200 rounded-lg text-sm font-medium text-indigo-800">
                                            {{ $res->kamar->nomor_kamar ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1.5 bg-gradient-to-r from-purple-100 to-purple-50 border border-purple-200 rounded-lg text-sm font-medium text-purple-800">
                                            {{ $res->kamar->tipeKamar->nama_tipe ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-sm">
                                            <div class="font-semibold text-gray-800">{{ $res->tanggal_checkin }}</div>
                                            <div class="text-gray-500">{{ $res->jam_checkin }}</div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-sm">
                                            <div class="font-semibold text-gray-800">{{ $res->tanggal_checkout }}</div>
                                            <div class="text-gray-500">{{ $res->jam_checkout }}</div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $statusToShow = $res->status_for_display ?? $res->status;
                                            $statusConfig = match($statusToShow) {
                                                'pending' => [
                                                    'bg' => 'bg-gradient-to-r from-yellow-100 to-yellow-50',
                                                    'border' => 'border-yellow-300',
                                                    'text' => 'text-yellow-800',
                                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                                ],
                                                'aktif' => [
                                                    'bg' => 'bg-gradient-to-r from-emerald-100 to-emerald-50',
                                                    'border' => 'border-emerald-300',
                                                    'text' => 'text-emerald-800',
                                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                                ],
                                                'selesai' => [
                                                    'bg' => 'bg-gradient-to-r from-blue-100 to-blue-50',
                                                    'border' => 'border-blue-300',
                                                    'text' => 'text-blue-800',
                                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                                ],
                                                'batal' => [
                                                    'bg' => 'bg-gradient-to-r from-red-100 to-red-50',
                                                    'border' => 'border-red-300',
                                                    'text' => 'text-red-800',
                                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                                                ],
                                                default => [
                                                    'bg' => 'bg-gradient-to-r from-gray-100 to-gray-50',
                                                    'border' => 'border-gray-300',
                                                    'text' => 'text-gray-800',
                                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                                ]
                                            };
                                        @endphp

                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 {{ $statusConfig['bg'] }} border {{ $statusConfig['border'] }} rounded-lg {{ $statusConfig['text'] }} font-semibold text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $statusConfig['icon'] !!}
                                            </svg>
                                            {{ ucfirst($statusToShow) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        {{-- Tombol Check-in --}}
                                        @if($res->can_checkin ?? false)
                                            <form action="{{ route('resepsionis.check.updateStatus', $res->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="aktif">
                                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium rounded-lg hover:from-emerald-600 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                                    </svg>
                                                    Check-in
                                                </button>
                                            </form>

                                        {{-- Tombol Check-out --}}
                                        @elseif($res->can_checkout ?? false)
                                            <form action="{{ route('resepsionis.check.updateStatus', $res->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md hover:shadow-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                    </svg>
                                                    Check-out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm font-medium">Tidak ada aksi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-100 rounded-full mb-4">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 font-semibold text-lg mb-1">Tidak ada data reservasi</p>
                                            <p class="text-gray-400 text-sm">Data akan muncul setelah ada reservasi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
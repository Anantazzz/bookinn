@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden mt-12 p-8">
    <h2 class="text-3xl font-bold text-center mb-8 tracking-wide text-gray-800">Detail Customer</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div>
            <p class="text-sm text-gray-600 uppercase mb-1">Nama</p>
            <p class="text-lg font-semibold text-gray-800">{{ $customer->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 uppercase mb-1">Email</p>
            <p class="text-lg font-semibold text-gray-800">{{ $customer->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 uppercase mb-1">Nomor HP</p>
            <p class="text-lg font-semibold text-gray-800">{{ $customer->no_hp ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 uppercase mb-1">Alamat</p>
            <p class="text-lg font-semibold text-gray-800">{{ $customer->alamat ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 uppercase mb-1">Terdaftar</p>
            <p class="text-lg font-semibold text-gray-800">{{ $customer->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 uppercase mb-1">Total Reservasi</p>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ $customer->reservasis->count() }}
            </span>
        </div>
    </div>

    @if($customer->reservasis->count() > 0)
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Riwayat Reservasi</h3>
            <div class="space-y-4">
                @foreach($customer->reservasis as $reservasi)
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Hotel & Kamar</p>
                            <p class="font-semibold text-gray-800">{{ $reservasi->kamar->hotel->nama_hotel ?? '-' }}</p>
                            <p class="text-sm text-gray-700">Kamar {{ $reservasi->kamar->nomor_kamar ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-700">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @if($reservasi->status == 'pending')
                                <span class="inline-block px-2 py-1 rounded text-sm bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($reservasi->status == 'aktif')
                                <span class="inline-block px-2 py-1 rounded text-sm bg-green-100 text-green-800">Aktif</span>
                            @elseif($reservasi->status == 'selesai')
                                <span class="inline-block px-2 py-1 rounded text-sm bg-blue-100 text-blue-800">Selesai</span>
                            @else
                                <span class="inline-block px-2 py-1 rounded text-sm bg-red-100 text-red-800">{{ ucfirst($reservasi->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.customers.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200">
           ← Kembali
        </a>
    </div>
</div>
@endsection
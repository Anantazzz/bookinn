@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 sm:py-8 px-3 sm:px-4">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2 sm:gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5z"/>
                    </svg>
                </div>
                <span>Manajemen Hotel</span>
            </h1>
            <button onclick="openAddModal()" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-full hover:scale-105 transition-all font-semibold text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Tambah Hotel
            </button>
        </div>

        @if (session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <span class="font-semibold text-sm sm:text-base">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">Gambar</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">Nama Hotel</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">Kota</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">Alamat</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">Bintang</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">No. Rekening</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase">Bank</th>
                            <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($hotels as $hotel)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 font-semibold text-gray-700">#{{ $hotel->id }}</td>
                            <td class="py-4 px-6">
                                @if ($hotel->gambar)
                                <img src="{{ asset('images/' . $hotel->gambar) }}" class="w-24 h-24 object-cover rounded-xl shadow-md">
                                @else
                                <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12z"/>
                                    </svg>
                                </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-900">{{ $hotel->nama_hotel }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ $hotel->kota }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ Str::limit($hotel->alamat, 30) }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full font-semibold">{{ $hotel->bintang }}</span>
                            </td>
                            <td class="py-4 px-6 text-gray-700">{{ $hotel->norek ?? '-' }}</td>
                            <td class="py-4 px-6">
                                @if($hotel->bank)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ strtoupper($hotel->bank) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition">Detail</a>
                                    <button onclick="openEditModal('{{ $hotel->id }}', '{{ $hotel->nama_hotel }}', '{{ $hotel->kota }}', '{{ $hotel->alamat }}', '{{ $hotel->bintang }}', '{{ $hotel->norek }}', '{{ $hotel->bank }}')" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition">Edit</button>
                                    <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus hotel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:scale-105 transition">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-gray-500 font-semibold">Belum ada data hotel</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Mobile Cards --}}
        <div class="lg:hidden space-y-4">
            @forelse ($hotels as $hotel)
            <div class="bg-white rounded-xl shadow-lg p-4">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        @if ($hotel->gambar)
                        <img src="{{ asset('images/' . $hotel->gambar) }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-lg">{{ $hotel->nama_hotel }}</h3>
                        <p class="text-sm text-gray-600 mb-1">{{ $hotel->kota }}</p>
                        <p class="text-xs text-gray-500 mb-2">{{ Str::limit($hotel->alamat, 50) }}</p>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="bg-yellow-50 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">⭐ {{ $hotel->bintang }}</span>
                            <span class="text-xs text-gray-500">ID: #{{ $hotel->id }}</span>
                        </div>
                        @if($hotel->norek)
                        <p class="text-xs text-gray-600 mb-2">Rekening: {{ $hotel->norek }}</p>
                        @endif
                        @if($hotel->bank)
                        <p class="text-xs text-gray-600 mb-3">Bank: 
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ strtoupper($hotel->bank) }}
                            </span>
                        </p>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="bg-cyan-500 text-white px-3 py-1 rounded-lg text-xs font-semibold">Detail</a>
                            <button onclick="openEditModal('{{ $hotel->id }}', '{{ $hotel->nama_hotel }}', '{{ $hotel->kota }}', '{{ $hotel->alamat }}', '{{ $hotel->bintang }}', '{{ $hotel->norek }}', '{{ $hotel->bank }}')" class="bg-amber-500 text-white px-3 py-1 rounded-lg text-xs font-semibold">Edit</button>
                            <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus hotel ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-semibold">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <p class="text-gray-500 font-semibold">Belum ada data hotel</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Modal Tambah Hotel -->
<div id="addModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300 ease-in-out">
    <div
        class="bg-gradient-to-br from-emerald-100 via-white to-green-50 rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 hover:scale-100">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-emerald-700 flex items-center gap-2">
                🏨 Tambah Hotel
            </h2>
            <button type="button" onclick="closeAddModal()"
                class="text-gray-500 hover:text-red-500 transition-colors duration-200 text-lg font-bold">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <input type="text" name="nama_hotel"
                    class="w-full border border-emerald-300 rounded-lg p-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="Nama Hotel">

                <input type="text" name="kota"
                    class="w-full border border-emerald-300 rounded-lg p-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="Kota">

                <textarea name="alamat" rows="3"
                    class="w-full border border-emerald-300 rounded-lg p-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="Alamat"></textarea>

                <input type="number" name="bintang"
                    class="w-full border border-emerald-300 rounded-lg p-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="Bintang (3-5)">

                <input type="text" name="norek"
                    class="w-full border border-emerald-300 rounded-lg p-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    placeholder="No. Rekening">

                <select name="bank"
                    class="w-full border border-emerald-300 rounded-lg p-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Pilih Bank</option>
                    <option value="mandiri">Bank Mandiri</option>
                    <option value="bca">Bank BCA</option>
                    <option value="bri">Bank BRI</option>
                    <option value="bni">Bank BNI</option>
                </select>

                <input type="file" name="gambar"
                    class="w-full border border-emerald-300 rounded-lg p-2 bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeAddModal()"
                    class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition duration-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-md transition duration-200">
                    ➕ Simpan Hotel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🌈 Modal Edit Hotel -->
<div id="editModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300 ease-in-out">
    <div
        class="bg-gradient-to-br from-indigo-100 via-white to-blue-50 rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 hover:scale-100">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-indigo-700 flex items-center gap-2">
                ✏️ Edit Hotel
            </h2>
            <button type="button" onclick="closeEditModal()"
                class="text-gray-500 hover:text-red-500 transition-colors duration-200 text-lg font-bold">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <input id="edit_nama_hotel" name="nama_hotel"
                    class="w-full border border-indigo-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Nama Hotel">

                <input id="edit_kota" name="kota"
                    class="w-full border border-indigo-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Kota">

                <textarea id="edit_alamat" name="alamat" rows="3"
                    class="w-full border border-indigo-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Alamat"></textarea>

                <input id="edit_bintang" name="bintang" type="number"
                    class="w-full border border-indigo-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Bintang (1-5)">

                <input id="edit_norek" name="norek"
                    class="w-full border border-indigo-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="No. Rekening">

                <select id="edit_bank" name="bank"
                    class="w-full border border-indigo-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Pilih Bank</option>
                    <option value="mandiri">Bank Mandiri</option>
                    <option value="bca">Bank BCA</option>
                    <option value="bri">Bank BRI</option>
                    <option value="bni">Bank BNI</option>
                </select>

                <input name="gambar" type="file"
                    class="w-full border border-indigo-300 rounded-lg p-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEditModal()"
                    class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition duration-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-md transition duration-200">
                     Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}
function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}
function openEditModal(id, nama, kota, alamat, bintang, norek, bank) {
    document.getElementById('editForm').action = '/admin/hotels/' + id;
    document.getElementById('edit_nama_hotel').value = nama;
    document.getElementById('edit_kota').value = kota;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_bintang').value = bintang;
    document.getElementById('edit_norek').value = norek;
    document.getElementById('edit_bank').value = bank || '';
    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

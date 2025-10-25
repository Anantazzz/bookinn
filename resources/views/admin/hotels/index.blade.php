@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                            </svg>
                        </div>
                        Manajemen Hotel
                    </h1>
                    <p class="text-gray-600">Kelola data hotel dengan mudah dan efisien</p>
                </div>
                <button 
                    onclick="openAddModal()" 
                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Tambah Hotel
                </button>
            </div>
        </div>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-slide-down">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gambar</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Hotel</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kota</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rating</th>
                            <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($hotels as $hotel)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-gray-700">#{{ $hotel->id }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($hotel->gambar)
                                        <div class="relative group">
                                            <img src="{{ asset('images/' . $hotel->gambar) }}" 
                                                 alt="{{ $hotel->nama_hotel }}" 
                                                 class="w-24 h-24 object-cover rounded-xl shadow-md group-hover:shadow-xl transition-all duration-200">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all duration-200"></div>
                                        </div>
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-gray-900">{{ $hotel->nama_hotel }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1 text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                        {{ $hotel->kota }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm text-gray-600">{{ Str::limit($hotel->alamat, 30) }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                        {{ $hotel->bintang }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.hotels.show', $hotel->id) }}" 
                                           class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                            Detail
                                        </a>

                                        <button 
                                            onclick="openEditModal('{{ $hotel->id }}', '{{ $hotel->nama_hotel }}', '{{ $hotel->kota }}', '{{ $hotel->bintang }}')" 
                                            class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus hotel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                                        </svg>
                                        <p class="text-gray-500 font-semibold text-lg">Belum ada data hotel</p>
                                        <p class="text-gray-400 text-sm">Klik tombol "Tambah Hotel" untuk menambahkan hotel baru</p>
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

{{-- ================== MODAL TAMBAH HOTEL ================== --}}
<div id="addHotelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate-slide-up">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl p-6">
      <h2 class="text-2xl font-bold text-white flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Tambah Hotel Baru
      </h2>
    </div>

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Hotel</label>
          <input type="text" name="nama_hotel" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan nama hotel" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
          <input type="text" name="kota" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan kota" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
          <textarea name="alamat" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan alamat lengkap" required></textarea>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Bintang</label>
          <input type="number" name="bintang" min="1" max="5" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="1-5" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Hotel</label>
          <input type="file" name="gambar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">Batal</button>
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-semibold">Simpan Hotel</button>
      </div>
    </form>
  </div>
</div>

{{-- ================== MODAL EDIT HOTEL ================== --}}
<div id="editHotelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate-slide-up">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl p-6">
      <h2 class="text-2xl font-bold text-white flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
        </svg>
        Edit Hotel
      </h2>
    </div>

    <form id="editHotelForm" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf
      @method('PUT')

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Hotel</label>
          <input type="text" name="nama_hotel" id="edit_nama_hotel" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
          <input type="text" name="kota" id="edit_kota" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Bintang</label>
          <input type="number" name="bintang" id="edit_bintang" min="1" max="5" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar (opsional)</label>
          <input type="file" name="gambar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">Batal</button>
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-semibold">Update Hotel</button>
      </div>
    </form>
  </div>
</div>

{{-- ================== STYLES ================== --}}
<style>
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}

.animate-slide-up {
  animation: slide-up 0.3s ease-out;
}

.animate-slide-down {
  animation: slide-down 0.3s ease-out;
}
</style>

{{-- ================== SCRIPT ================== --}}
<script>
function openAddModal() {
  document.getElementById('addHotelModal').classList.remove('hidden');
}
function closeAddModal() {
  document.getElementById('addHotelModal').classList.add('hidden');
}

function openEditModal(id, nama, kota, bintang) {
  document.getElementById('editHotelModal').classList.remove('hidden');
  document.getElementById('edit_nama_hotel').value = nama;
  document.getElementById('edit_kota').value = kota;
  document.getElementById('edit_bintang').value = bintang;
  document.getElementById('editHotelForm').action = `/admin/hotels/${id}`;
}
function closeEditModal() {
  document.getElementById('editHotelModal').classList.add('hidden');
}
</script>
@endsection
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Hotel</h1>
        <button 
            onclick="openAddModal()" 
            class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Hotel
        </button>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
            <tr>
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Gambar</th>
                <th class="py-3 px-4 text-left">Nama Hotel</th>
                <th class="py-3 px-4 text-left">Kota</th>
                <th class="py-3 px-4 text-left">Alamat</th>
                <th class="py-3 px-4 text-left">Bintang</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($hotels as $hotel)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $hotel->id }}</td>
                    <td class="px-4 py-2">
                        @if ($hotel->gambar)
                            <img src="{{ asset('images/' . $hotel->gambar) }}" 
                                 alt="{{ $hotel->nama_hotel }}" 
                                 class="w-20 h-20 object-cover rounded-lg shadow-sm">
                        @else
                            <span class="text-gray-400 italic">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $hotel->nama_hotel }}</td>
                    <td class="py-3 px-4">{{ $hotel->kota }}</td>
                    <td class="py-3 px-4">{{ $hotel->alamat }}</td>
                    <td class="py-3 px-4">{{ $hotel->bintang }}</td>
                    <td class="py-3 px-4 space-x-2">
                        <a href="{{ route('admin.hotels.show', $hotel->id) }}" 
                           class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">
                           Detail
                        </a>

                        <button 
                            onclick="openEditModal('{{ $hotel->id }}', '{{ $hotel->nama_hotel }}', '{{ $hotel->kota }}', '{{ $hotel->bintang }}')" 
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </button>

                        <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" 
                              method="POST" class="inline" 
                              onsubmit="return confirm('Yakin ingin menghapus hotel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-600 py-4">
                        Belum ada data hotel.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ================== MODAL TAMBAH HOTEL ================== --}}
<div id="addHotelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Tambah Hotel</h2>

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="block text-gray-700">Nama Hotel</label>
        <input type="text" name="nama_hotel" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Kota</label>
        <input type="text" name="kota" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Alamat</label>
        <input type="text" name="alamat" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Bintang</label>
        <input type="number" name="bintang" min="1" max="5" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Gambar</label>
        <input type="file" name="gambar" class="w-full border rounded-lg p-2">
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ================== MODAL EDIT HOTEL ================== --}}
<div id="editHotelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Hotel</h2>

    <form id="editHotelForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="block text-gray-700">Nama Hotel</label>
        <input type="text" name="nama_hotel" id="edit_nama_hotel" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Kota</label>
        <input type="text" name="kota" id="edit_kota" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Bintang</label>
        <input type="number" name="bintang" id="edit_bintang" min="1" max="5" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Ganti Gambar (opsional)</label>
        <input type="file" name="gambar" class="w-full border rounded-lg p-2">
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
      </div>
    </form>
  </div>
</div>

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

@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Resepsionis</h1>
        <button 
            onclick="openAddModal()" 
            class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Data
        </button>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
            <tr>
                <th class="py-3 px-4 text-left">Hotel</th>
                <th class="py-3 px-4 text-left">Nama</th>
                <th class="py-3 px-4 text-left">Email</th>
                <th class="py-3 px-4 text-left">No HP</th>
                <th class="py-3 px-4 text-left">Alamat</th>
                <th class="py-3 px-4 text-left">Shift</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($resepsionis as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $r->hotel->nama_hotel ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $r->name }}</td>
                    <td class="py-3 px-4">{{ $r->email }}</td>
                    <td class="py-3 px-4">{{ $r->no_hp ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $r->alamat ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $r->shift ?? 'Belum ditentukan' }}</td>

                    <td class="py-3 px-4 space-x-2">
                        <a href="{{ route('admin.resepsionis.show', $r->id) }}" 
                           class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">
                           Detail
                        </a>

                        <button 
                            onclick="openEditModal('{{ $r->id }}', '{{ $r->name }}', '{{ $r->email }}', '{{ $r->no_hp }}', '{{ $r->alamat }}', '{{ $r->shift }}', '{{ $r->hotel_id }}')"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </button>

                        <form action="{{ route('admin.resepsionis.destroy', $r->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin mau hapus resepsionis ini?')" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-600">
                        Belum ada data resepsionis.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ================== MODAL TAMBAH RESEPSIONIS ================== --}}
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Tambah Resepsionis</h2>

    <form action="{{ route('admin.resepsionis.store') }}" method="POST">
      @csrf

        <div class="mb-3">
        <label class="block text-gray-700">Pilih Hotel</label>
        <select name="hotel_id" class="w-full border rounded-lg p-2">
          <option value="">-- Pilih Hotel --</option>
          @foreach ($hotels as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Nama</label>
        <input type="text" name="name" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">No HP</label>
        <input type="text" name="no_hp" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Alamat</label>
        <input type="text" name="alamat" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Shift</label>
        <select name="shift" class="w-full border rounded-lg p-2">
          <option value="">-- Pilih Shift --</option>
          <option value="pagi">Pagi</option>
          <option value="malam">Malam</option>
        </select>
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ================== MODAL EDIT RESEPSIONIS ================== --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Resepsionis</h2>

    <form id="editForm" method="POST">
      @csrf
      @method('PUT')

        <div class="mb-3">
        <label class="block text-gray-700">Pilih Hotel</label>
        <select name="hotel_id" id="edit_hotel_id" class="w-full border rounded-lg p-2">
          @foreach ($hotels as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Nama</label>
        <input type="text" name="name" id="edit_name" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" id="edit_email" class="w-full border rounded-lg p-2" required>
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">No HP</label>
        <input type="text" name="no_hp" id="edit_no_hp" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Alamat</label>
        <input type="text" name="alamat" id="edit_alamat" class="w-full border rounded-lg p-2">
      </div>

      <div class="mb-3">
        <label class="block text-gray-700">Shift</label>
        <select name="shift" id="edit_shift" class="w-full border rounded-lg p-2">
          <option value="Pagi">Pagi</option>
          <option value="Malam">Malam</option>
        </select>
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
  document.getElementById('addModal').classList.remove('hidden');
}
function closeAddModal() {
  document.getElementById('addModal').classList.add('hidden');
}

function openEditModal(id, name, email, no_hp, alamat, shift, hotel_id) {
  document.getElementById('editModal').classList.remove('hidden');
  document.getElementById('edit_hotel_id').value = hotel_id;
  document.getElementById('edit_name').value = name;
  document.getElementById('edit_email').value = email;
  document.getElementById('edit_no_hp').value = no_hp;
  document.getElementById('edit_alamat').value = alamat;
  document.getElementById('edit_shift').value = shift;
  document.getElementById('editForm').action = `/admin/resepsionis/${id}`;
}
function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

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
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                        </div>
                        Manajemen Resepsionis
                    </h1>
                    <p class="text-gray-600">Kelola data resepsionis hotel dengan mudah dan efisien</p>
                </div>
                <button 
                    onclick="openAddModal()" 
                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Tambah Resepsionis
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
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hotel</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No HP</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Shift</th>
                            <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($resepsionis as $r)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                                        </svg>
                                        <span class="font-semibold text-gray-900">{{ $r->hotel->nama_hotel ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-gray-700">{{ $r->name }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm text-gray-600">{{ $r->email }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-gray-600">{{ $r->no_hp ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm text-gray-600">{{ Str::limit($r->alamat ?? '-', 30) }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($r->shift == 'pagi')
                                        <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full font-semibold text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                            </svg>
                                            Pagi
                                        </span>
                                    @elseif($r->shift == 'malam')
                                        <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-semibold text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
                                            </svg>
                                            Malam
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.resepsionis.show', $r->id) }}" 
                                           class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                            Detail
                                        </a>

                                        <button 
                                            onclick="openEditModal('{{ $r->id }}', '{{ $r->name }}', '{{ $r->email }}', '{{ $r->no_hp }}', '{{ $r->alamat }}', '{{ $r->shift }}', '{{ $r->hotel_id }}')"
                                            class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.resepsionis.destroy', $r->id) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus resepsionis ini?')">
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
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                        </svg>
                                        <p class="text-gray-500 font-semibold text-lg">Belum ada data resepsionis</p>
                                        <p class="text-gray-400 text-sm">Klik tombol "Tambah Resepsionis" untuk menambahkan data baru</p>
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

{{-- ================== MODAL TAMBAH RESEPSIONIS ================== --}}
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate-slide-up max-h-[90vh] overflow-y-auto">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl p-6 sticky top-0 z-10">
      <h2 class="text-2xl font-bold text-white flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Tambah Resepsionis Baru
      </h2>
    </div>

    <form action="{{ route('admin.resepsionis.store') }}" method="POST" class="p-6">
      @csrf

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Hotel</label>
          <select name="hotel_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
            <option value="">-- Pilih Hotel --</option>
            @foreach ($hotels as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
          <input type="text" name="name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan nama lengkap" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="contoh@email.com" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Minimal 8 karakter" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">No HP</label>
          <input type="text" name="no_hp" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="08xxxxxxxxxx">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
          <textarea name="alamat" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan alamat lengkap"></textarea>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Shift Kerja</label>
          <select name="shift" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            <option value="">-- Pilih Shift --</option>
            <option value="pagi">Pagi</option>
            <option value="malam">Malam</option>
          </select>
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">Batal</button>
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-semibold">Simpan Resepsionis</button>
      </div>
    </form>
  </div>
</div>

{{-- ================== MODAL EDIT RESEPSIONIS ================== --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate-slide-up max-h-[90vh] overflow-y-auto">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl p-6 sticky top-0 z-10">
      <h2 class="text-2xl font-bold text-white flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
        </svg>
        Edit Resepsionis
      </h2>
    </div>

    <form id="editForm" method="POST" class="p-6">
      @csrf
      @method('PUT')

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Hotel</label>
          <select name="hotel_id" id="edit_hotel_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
            @foreach ($hotels as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
          <input type="text" name="name" id="edit_name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" name="email" id="edit_email" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">No HP</label>
          <input type="text" name="no_hp" id="edit_no_hp" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
          <textarea name="alamat" id="edit_alamat" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"></textarea>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Shift Kerja</label>
          <select name="shift" id="edit_shift" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
            <option value="pagi">Pagi</option>
            <option value="malam">Malam</option>
          </select>
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">Batal</button>
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-semibold">Update Resepsionis</button>
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
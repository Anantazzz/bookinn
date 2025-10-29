@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 sm:py-8 px-3 sm:px-4">
  <div class="max-w-7xl mx-auto">
      
      {{-- Header Section --}}
      <div class="mb-6 sm:mb-8">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div>
                  <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 flex items-center gap-2 sm:gap-3">
                      <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                              <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0-0-6 3 3 0 0 0 0 6z"/>
                          </svg>
                      </div>
                      <span class="break-words">Manajemen Owner</span>
                  </h1>
              </div>
              <button 
                  onclick="openModal('addOwnerModal')" 
                  class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm sm:text-base w-full md:w-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                  </svg>
                  Tambah Data
              </button>
          </div>
      </div>

      {{-- Notifikasi sukses --}}
      @if (session('success'))
          <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl mb-4 sm:mb-6 shadow-sm flex items-center gap-2 sm:gap-3 animate-slide-down">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </svg>
              <span class="font-semibold text-sm sm:text-base">{{ session('success') }}</span>
          </div>
      @endif

      {{-- Table Card - Desktop View --}}
      <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden">
          <div class="overflow-x-auto">
              <table class="min-w-full">
                  <thead>
                      <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hotel</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No HP</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat</th>
                          <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                      @forelse ($owners as $index => $owner)
                          <tr class="hover:bg-gray-50 transition-colors duration-150">
                              <td class="py-4 px-6 font-semibold text-gray-800">{{ $owner->name }}</td>
                              <td class="py-4 px-6 text-gray-700">{{ $owner->hotel->nama_hotel ?? '-' }}</td>
                              <td class="py-4 px-6 text-sm text-gray-600">{{ $owner->email }}</td>
                              <td class="py-4 px-6 text-gray-600">{{ $owner->no_hp ?? '-' }}</td>
                              <td class="py-4 px-6 text-sm text-gray-600">{{ Str::limit($owner->alamat ?? '-', 30) }}</td>
                              <td class="py-4 px-6 text-center">
                                  <div class="flex justify-center gap-2">
                                      <a href="{{ route('admin.owners.show', $owner->id) }}" 
                                          class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                          Detail
                                      </a>
                                      <button 
                                          onclick="openEditModal({{ $owner->id }}, '{{ $owner->name }}', '{{ $owner->hotel_id }}', '{{ $owner->email }}', '{{ $owner->no_hp ?? '' }}', '{{ $owner->alamat ?? '' }}')" 
                                          class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                          Edit
                                      </button>
                                      <form action="{{ route('admin.owners.destroy', $owner->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
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
                                      <p class="text-gray-500 font-semibold text-lg">Belum ada data owner</p>
                                      <p class="text-gray-400 text-sm">Klik tombol "Tambah Owner" untuk menambahkan data baru</p>
                                  </div>
                              </td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>

      {{-- Card View - Mobile & Tablet --}}
      <div class="lg:hidden space-y-4">
          @forelse ($owners as $index => $owner)
              <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                  <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                      <div class="flex items-center justify-between">
                          <span class="text-sm font-bold text-gray-700">Owner #{{ $index + 1 }}</span>
                          <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-semibold">
                              {{ $owner->hotel->nama_hotel ?? '-' }}
                          </span>
                      </div>
                  </div>
                  <div class="p-4 space-y-3">
                      <div>
                          <p class="text-xs text-gray-500 mb-1">Nama</p>
                          <p class="font-semibold text-gray-800">{{ $owner->name }}</p>
                      </div>
                      <div>
                          <p class="text-xs text-gray-500 mb-1">Email</p>
                          <p class="text-sm text-gray-600 break-all">{{ $owner->email }}</p>
                      </div>
                      <div class="grid grid-cols-2 gap-3">
                          <div>
                              <p class="text-xs text-gray-500 mb-1">No HP</p>
                              <p class="text-sm text-gray-600">{{ $owner->no_hp ?? '-' }}</p>
                          </div>
                          <div>
                              <p class="text-xs text-gray-500 mb-1">Alamat</p>
                              <p class="text-sm text-gray-600">{{ Str::limit($owner->alamat ?? '-', 20) }}</p>
                          </div>
                      </div>
                      <div class="pt-3 flex flex-col sm:flex-row gap-2">
                          <a href="{{ route('admin.owners.show', $owner->id) }}" 
                              class="flex-1 bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-200 font-semibold text-sm text-center">
                              Detail
                          </a>
                          <button 
                              onclick="openEditModal({{ $owner->id }}, '{{ $owner->name }}', '{{ $owner->hotel_id }}', '{{ $owner->email }}', '{{ $owner->no_hp ?? '' }}', '{{ $owner->alamat ?? '' }}')" 
                              class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-200 font-semibold text-sm">
                              Edit
                          </button>
                          <form action="{{ route('admin.owners.destroy', $owner->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" 
                                  class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-200 font-semibold text-sm">
                                  Hapus
                              </button>
                          </form>
                      </div>
                  </div>
              </div>
          @empty
              <div class="bg-white rounded-xl shadow-lg p-8">
                  <div class="flex flex-col items-center gap-3">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                          <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                      </svg>
                      <p class="text-gray-500 font-semibold text-base sm:text-lg text-center">Belum ada data owner</p>
                      <p class="text-gray-400 text-xs sm:text-sm text-center">Klik tombol "Tambah Owner" untuk menambahkan data baru</p>
                  </div>
              </div>
          @endforelse
      </div>
  </div>
</div>

{{-- ==================== MODAL TAMBAH OWNER ==================== --}}
<div id="addOwnerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate-slide-up max-h-[90vh] overflow-y-auto">
      <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl p-4 sm:p-6 sticky top-0 z-10">
          <h2 class="text-xl sm:text-2xl font-bold text-white">Tambah Owner</h2>
      </div>
      <form action="{{ route('admin.owners.store') }}" method="POST" class="p-4 sm:p-6 space-y-4">
          @csrf
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Nama Lengkap</label>
              <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Email</label>
              <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Password</label>
              <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Nomor Telepon</label>
              <input type="text" name="no_hp" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base">
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Hotel yang Dimiliki</label>
              <select name="hotel_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
                  <option value="">-- Pilih Hotel --</option>
                  @foreach ($hotels as $hotel)
                      <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Alamat</label>
              <textarea name="alamat" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base"></textarea>
          </div>
          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
              <button type="button" onclick="closeModal('addOwnerModal')" class="px-5 py-2 bg-gray-400 text-white rounded-lg hover:opacity-90 text-sm sm:text-base order-2 sm:order-1">Batal</button>
              <button type="submit" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg hover:scale-105 transition-all text-sm sm:text-base order-1 sm:order-2">Simpan</button>
          </div>
      </form>
  </div>
</div>

{{-- ==================== MODAL EDIT OWNER ==================== --}}
<div id="editOwnerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate-slide-up max-h-[90vh] overflow-y-auto">
      <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl p-4 sm:p-6 sticky top-0 z-10">
          <h2 class="text-xl sm:text-2xl font-bold text-white">Edit Owner</h2>
      </div>
      <form id="editOwnerForm" method="POST" class="p-4 sm:p-6 space-y-4">
          @csrf
          @method('PUT')
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Nama Lengkap</label>
              <input type="text" id="editName" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Email</label>
              <input type="email" id="editEmail" name="email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Nomor Telepon</label>
              <input type="text" id="editNoHp" name="no_hp" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base">
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Hotel yang Dimiliki</label>
              <select id="editHotel" name="hotel_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base" required>
                  <option value="">-- Pilih Hotel --</option>
                  @foreach ($hotels as $hotel)
                      <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label class="block text-gray-700 mb-2 font-medium text-sm sm:text-base">Alamat</label>
              <textarea id="editAlamat" name="alamat" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm sm:text-base"></textarea>
          </div>
          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
              <button type="button" onclick="closeModal('editOwnerModal')" class="px-5 py-2 bg-gray-400 text-white rounded-lg hover:opacity-90 text-sm sm:text-base order-2 sm:order-1">Batal</button>
              <button type="submit" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg hover:scale-105 transition-all text-sm sm:text-base order-1 sm:order-2">Perbarui</button>
          </div>
      </form>
  </div>
</div>

<script>
  function openModal(id) {
      document.getElementById(id).classList.remove('hidden');
  }

  function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
  }

  function openEditModal(id, name, hotel_id, email, no_hp, alamat) {
      document.getElementById('editOwnerForm').action = `/admin/owners/${id}`;
      document.getElementById('editName').value = name;
      document.getElementById('editHotel').value = hotel_id;
      document.getElementById('editEmail').value = email;
      document.getElementById('editNoHp').value = no_hp;
      document.getElementById('editAlamat').value = alamat;
      openModal('editOwnerModal');
  }
</script>
@endsection
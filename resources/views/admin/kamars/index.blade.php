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
                        Manajemen Kamar
                    </h1>
                    <p class="text-gray-600">Kelola data kamar hotel dengan mudah dan efisien</p>
                </div>
                <button 
                    onclick="openAddModal()" 
                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Tambah Kamar
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
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hotel</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nomor</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kapasitas</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bed</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Internet</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Harga</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kamars as $kamar)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-gray-700">#{{ $kamar->id }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($kamar->gambar)
                                        <div class="relative group">
                                            <img src="{{ asset('images/kamars/' . $kamar->gambar) }}" 
                                                 alt="Kamar {{ $kamar->nomor_kamar }}" 
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
                                    <div class="font-semibold text-gray-900">{{ $kamar->hotel->nama_hotel ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm text-gray-600">{{ $kamar->tipeKamar->nama_tipe ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-gray-700">{{ $kamar->nomor_kamar }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1 text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                        </svg>
                                        {{ $kamar->kapasitas }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-gray-600">{{ $kamar->jumlah_bed }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($kamar->internet)
                                        <span class="inline-flex items-center gap-1 text-green-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z"/>
                                                <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
                                            </svg>
                                            Ada
                                        </span>
                                    @else
                                        <span class="text-gray-400">Tidak</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-blue-600">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($kamar->status == 'tersedia')
                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full font-semibold text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-700 px-3 py-1 rounded-full font-semibold text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                            </svg>
                                            Booking
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.kamars.show', $kamar->id) }}" 
                                           class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                            Detail
                                        </a>

                                        <button 
                                            onclick="openEditModal('{{ $kamar->id }}', '{{ $kamar->hotel_id }}', '{{ $kamar->tipe_kamar_id }}', '{{ $kamar->nomor_kamar }}', '{{ $kamar->harga }}', '{{ $kamar->kapasitas }}', '{{ $kamar->jumlah_bed }}', '{{ $kamar->internet }}', '{{ $kamar->status }}', '{{ $kamar->gambar }}')" 
                                            class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.kamars.destroy', $kamar->id) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
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
                                <td colspan="11" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                                        </svg>
                                        <p class="text-gray-500 font-semibold text-lg">Belum ada data kamar</p>
                                        <p class="text-gray-400 text-sm">Klik tombol "Tambah Kamar" untuk menambahkan kamar baru</p>
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

{{-- ================== MODAL TAMBAH KAMAR ================== --}}
<div id="addKamarModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl relative animate-slide-up max-h-[90vh] overflow-y-auto">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl p-6 sticky top-0 z-10">
      <h2 class="text-2xl font-bold text-white flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Tambah Kamar Baru
      </h2>
    </div>

    <form action="{{ route('admin.kamars.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Hotel</label>
          <select name="hotel_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
            <option value="">-- Pilih Hotel --</option>
            @foreach(\App\Models\Hotel::all() as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kamar</label>
          <select name="tipe_kamar_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
            <option value="">-- Pilih Tipe --</option>
            @foreach(\App\Models\TipeKamar::all() as $tipe)
              <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kamar</label>
          <input type="text" name="nomor_kamar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Contoh: 101" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
          <input type="number" name="harga" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Contoh: 500000" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas</label>
          <input type="number" name="kapasitas" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Jumlah orang" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bed</label>
          <input type="number" name="jumlah_bed" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Jumlah tempat tidur" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Internet</label>
          <select name="internet" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            <option value="1">Ada</option>
            <option value="0">Tidak</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
          <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            <option value="tersedia">Tersedia</option>
            <option value="booking">Booking</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Kamar</label>
          <input type="file" name="gambar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required accept="image/*">
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">Batal</button>
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-semibold">Simpan Kamar</button>
      </div>
    </form>
  </div>
</div>

{{-- ================== MODAL EDIT KAMAR ================== --}}
<div id="editKamarModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl relative animate-slide-up max-h-[90vh] overflow-y-auto">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl p-6 sticky top-0 z-10">
      <h2 class="text-2xl font-bold text-white flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
        </svg>
        Edit Kamar
      </h2>
    </div>

    <form id="editKamarForm" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Hotel</label>
          <select id="edit_hotel_id" name="hotel_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
            @foreach(\App\Models\Hotel::all() as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kamar</label>
          <select id="edit_tipe_kamar_id" name="tipe_kamar_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
            @foreach(\App\Models\TipeKamar::all() as $tipe)
              <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kamar</label>
          <input type="text" id="edit_nomor_kamar" name="nomor_kamar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
          <input type="number" id="edit_harga" name="harga" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas</label>
          <input type="number" id="edit_kapasitas" name="kapasitas" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bed</label>
          <input type="number" id="edit_bed" name="jumlah_bed" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" required>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Internet</label>
          <select id="edit_internet" name="internet" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
            <option value="1">Ada</option>
            <option value="0">Tidak</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
          <select id="edit_status" name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
            <option value="tersedia">Tersedia</option>
            <option value="booking">Booking</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar (opsional)</label>
          <input type="file" name="gambar" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" accept="image/*">
          <div id="preview_gambar_container" class="mt-3 hidden">
            <img id="preview_gambar" src="" class="w-32 h-32 object-cover rounded-xl shadow-md">
          </div>
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">Batal</button>
        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:scale-105 transition-all font-semibold">Update Kamar</button>
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
  document.getElementById('addKamarModal').classList.remove('hidden');
}

function closeAddModal() {
  document.getElementById('addKamarModal').classList.add('hidden');
}

function openEditModal(id, hotelId, tipeId, nomor, harga, kapasitas, bed, internet, status, gambar) {
  document.getElementById('editKamarModal').classList.remove('hidden');
  document.getElementById('editKamarForm').action = `/admin/kamars/${id}`;
  document.getElementById('edit_hotel_id').value = hotelId;
  document.getElementById('edit_tipe_kamar_id').value = tipeId;
  document.getElementById('edit_nomor_kamar').value = nomor;
  document.getElementById('edit_harga').value = harga;
  document.getElementById('edit_kapasitas').value = kapasitas;
  document.getElementById('edit_bed').value = bed;
  document.getElementById('edit_internet').value = internet;
  document.getElementById('edit_status').value = status;
  
  const previewContainer = document.getElementById('preview_gambar_container');
  const previewImg = document.getElementById('preview_gambar');
  
  if (gambar) {
    previewImg.src = `/images/kamars/${gambar}`;
    previewContainer.classList.remove('hidden');
  } else {
    previewContainer.classList.add('hidden');
  }
}

function closeEditModal() {
  document.getElementById('editKamarModal').classList.add('hidden');
}
</script>
@endsection
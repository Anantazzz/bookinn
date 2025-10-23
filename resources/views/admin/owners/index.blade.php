@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Owner</h1>
        <button onclick="openModal('addOwnerModal')"
            class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">
            Tambah Owner
        </button>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
            <tr>
                <th class="py-3 px-4 text-left">ID</th>
                <th class="py-3 px-4 text-left">Nama</th>
                <th class="py-3 px-4 text-left">Hotel</th>
                <th class="py-3 px-4 text-left">Email</th>
                <th class="py-3 px-4 text-left">No Hp</th>
                <th class="py-3 px-4 text-left">Alamat</th>
                <th class="py-3 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($owners as $index => $owner)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $owner->name }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $owner->hotel->nama_hotel ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $owner->email }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $owner->no_hp ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $owner->alamat ?? '-' }}</td>

                    <td class="py-3 px-4 space-x-2">
                        {{-- Detail --}}
                        <a href="{{ route('admin.owners.show', $owner->id) }}" 
                           class="bg-sky-500 text-white px-3 py-1 rounded hover:bg-sky-600">Detail</a>

                        {{-- Edit --}}
                        <button onclick="openEditModal({{ $owner->id }}, '{{ $owner->name }}', '{{ $owner->hotel_id }}', '{{ $owner->email }}', '{{ $owner->no_hp ?? '' }}', '{{ $owner->alamat ?? '' }}')"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</button>

                        {{-- Hapus --}}
                        <form action="{{ route('admin.owners.destroy', $owner->id) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus owner ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-600 py-4">
                        Belum ada data owner.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ==================== MODAL TAMBAH OWNER ==================== --}}
<div id="addOwnerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Tambah Owner</h2>
        <form action="{{ route('admin.owners.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 text-gray-700">Nomor Telepon</label>
                    <input type="text" name="no_hp" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="col-span-2">
                    <label class="block mb-2 text-gray-700">Hotel yang Dimiliki</label>
                    <select name="hotel_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">-- Pilih Hotel --</option>
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block mb-2 text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal('addOwnerModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL EDIT OWNER ==================== --}}
<div id="editOwnerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Owner</h2>
        <form id="editOwnerForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-gray-700">Nama Lengkap</label>
                    <input type="text" id="editName" name="name" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 text-gray-700">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 text-gray-700">Nomor Telepon</label>
                    <input type="text" id="editNoHp" name="no_hp" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="col-span-2">
                    <label class="block mb-2 text-gray-700">Hotel yang Dimiliki</label>
                    <select id="editHotel" name="hotel_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">-- Pilih Hotel --</option>
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block mb-2 text-gray-700">Alamat</label>
                    <textarea id="editAlamat" name="alamat" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal('editOwnerModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== SCRIPT MODAL ==================== --}}
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

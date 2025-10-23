@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Kelola Kamar</h2>
        <button id="btnTambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Tambah Kamar
        </button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="py-2 px-3">ID</th>
                    <th class="py-2 px-3">Gambar</th>
                    <th class="py-2 px-3">Hotel</th>
                    <th class="py-2 px-3">Tipe</th>
                    <th class="py-2 px-3">Nomor</th>
                    <th class="py-2 px-3">Kapasitas</th>
                    <th class="py-2 px-3">Bed</th>
                    <th class="py-2 px-3">Internet</th>
                    <th class="py-2 px-3">Harga</th>
                    <th class="py-2 px-3">Status</th>
                    <th class="py-2 px-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kamars as $kamar)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-3">{{ $kamar->id }}</td>
                    <td class="py-2 px-3">
                        @if($kamar->gambar)
                            <img src="{{ asset('images/kamars/' . $kamar->gambar) }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <span class="text-gray-400 italic">Tidak ada</span>
                        @endif
                    </td>
                    <td class="py-2 px-3">{{ $kamar->hotel->nama_hotel ?? '-' }}</td>
                    <td class="py-2 px-3">{{ $kamar->tipeKamar->nama_tipe ?? '-' }}</td>
                    <td class="py-2 px-3">{{ $kamar->nomor_kamar }}</td>
                    <td class="py-2 px-3">{{ $kamar->kapasitas }}</td>
                    <td class="py-2 px-3">{{ $kamar->jumlah_bed }}</td>
                    <td class="py-2 px-3">{{ $kamar->internet ? 'Ada' : 'Tidak' }}</td>
                    <td class="py-2 px-3">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                    <td class="py-2 px-3">{{ ucfirst($kamar->status) }}</td>
                    <td class="py-2 px-3 text-center space-x-1">

                         {{-- Tombol Detail --}}
                        <a href="{{ route('admin.kamars.show', $kamar->id) }}" 
                           class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1 rounded">
                            Detail
                        </a>

                        {{-- Tombol Edit dan Hapus --}}
                        <button 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded editBtn"
                            data-id="{{ $kamar->id }}"
                            data-hotel="{{ $kamar->hotel_id }}"
                            data-tipe="{{ $kamar->tipe_kamar_id }}"
                            data-nomor="{{ $kamar->nomor_kamar }}"
                            data-harga="{{ $kamar->harga }}"
                            data-kapasitas="{{ $kamar->kapasitas }}"
                            data-bed="{{ $kamar->jumlah_bed }}"
                            data-internet="{{ $kamar->internet }}"
                            data-status="{{ $kamar->status }}"
                            data-gambar="{{ $kamar->gambar }}">
                            Edit
                        </button>

                        <form action="{{ route('admin.kamars.destroy', $kamar->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus kamar ini?')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-lg">
        <h3 class="text-xl font-bold mb-4 text-gray-700">Tambah Kamar</h3>
        <form action="{{ route('admin.kamars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Hotel</label>
                    <select name="hotel_id" class="w-full border rounded-lg p-2" required>
                        <option value="">-- Pilih Hotel --</option>
                        @foreach(\App\Models\Hotel::all() as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tipe Kamar</label>
                    <select name="tipe_kamar_id" class="w-full border rounded-lg p-2" required>
                        @foreach(\App\Models\TipeKamar::all() as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Nomor Kamar</label><input type="text" name="nomor_kamar" class="w-full border rounded-lg p-2" required></div>
                <div><label>Harga</label><input type="number" name="harga" class="w-full border rounded-lg p-2" required></div>
                <div><label>Kapasitas</label><input type="number" name="kapasitas" class="w-full border rounded-lg p-2" required></div>
                <div><label>Jumlah Bed</label><input type="number" name="jumlah_bed" class="w-full border rounded-lg p-2" required></div>
                <div>
                    <label>Internet</label>
                    <select name="internet" class="w-full border rounded-lg p-2">
                        <option value="1">Ada</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status" class="w-full border rounded-lg p-2">
                        <option value="tersedia">Tersedia</option>
                        <option value="booking">Booking</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label>Upload Gambar</label>
                    <input type="file" name="gambar" class="w-full border rounded-lg p-2" required accept="image/*">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" id="btnCloseTambah" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-lg">
        <h3 class="text-xl font-bold mb-4 text-gray-700">Edit Kamar</h3>
        <form id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Hotel</label>
                    <select id="edit_hotel_id" name="hotel_id" class="w-full border rounded-lg p-2" required>
                        @foreach(\App\Models\Hotel::all() as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->nama_hotel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tipe Kamar</label>
                    <select id="edit_tipe_kamar_id" name="tipe_kamar_id" class="w-full border rounded-lg p-2" required>
                        @foreach(\App\Models\TipeKamar::all() as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Nomor</label><input id="edit_nomor_kamar" name="nomor_kamar" type="text" class="w-full border rounded-lg p-2"></div>
                <div><label>Harga</label><input id="edit_harga" name="harga" type="number" class="w-full border rounded-lg p-2"></div>
                <div><label>Kapasitas</label><input id="edit_kapasitas" name="kapasitas" type="number" class="w-full border rounded-lg p-2"></div>
                <div><label>Jumlah Bed</label><input id="edit_bed" name="jumlah_bed" type="number" class="w-full border rounded-lg p-2"></div>
                <div>
                    <label>Internet</label>
                    <select id="edit_internet" name="internet" class="w-full border rounded-lg p-2">
                        <option value="1">Ada</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select id="edit_status" name="status" class="w-full border rounded-lg p-2">
                        <option value="tersedia">Tersedia</option>
                        <option value="booking">Booking</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label>Ganti Gambar</label>
                    <input type="file" id="edit_gambar" name="gambar" class="w-full border rounded-lg p-2" accept="image/*">
                    <img id="preview_gambar" src="" class="w-32 h-32 object-cover mt-2 rounded-lg hidden">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" id="btnCloseEdit" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
const modalTambah = document.getElementById('modalTambah');
const modalEdit = document.getElementById('modalEdit');

document.getElementById('btnTambah').onclick = () => modalTambah.classList.replace('hidden', 'flex');
document.getElementById('btnCloseTambah').onclick = () => modalTambah.classList.add('hidden');
document.getElementById('btnCloseEdit').onclick = () => modalEdit.classList.add('hidden');

document.querySelectorAll('.editBtn').forEach(btn => {
    btn.onclick = () => {
        const id = btn.dataset.id;
        document.getElementById('formEdit').action = `/admin/kamars/${id}`;
        document.getElementById('edit_hotel_id').value = btn.dataset.hotel;
        document.getElementById('edit_tipe_kamar_id').value = btn.dataset.tipe;
        document.getElementById('edit_nomor_kamar').value = btn.dataset.nomor;
        document.getElementById('edit_harga').value = btn.dataset.harga;
        document.getElementById('edit_kapasitas').value = btn.dataset.kapasitas;
        document.getElementById('edit_bed').value = btn.dataset.bed;
        document.getElementById('edit_internet').value = btn.dataset.internet;
        document.getElementById('edit_status').value = btn.dataset.status;

        const img = document.getElementById('preview_gambar');
        if (btn.dataset.gambar) {
            img.src = `/images/kamars/${btn.dataset.gambar}`;
            img.classList.remove('hidden');
        } else {
            img.classList.add('hidden');
        }

        modalEdit.classList.replace('hidden', 'flex');
    }
});
</script>
@endsection

<div>
    <div class="bg-white shadow rounded-4 p-3 d-flex flex-column flex-lg-row align-items-stretch gap-3">
    <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
    <div class="d-flex align-items-center mb-1">
        <span class="fw-semibold">Mau kemana?</span>
    </div>
   <select class="form-select border-0 shadow-none p-0" name="kota">
    <option value="" disabled selected>Pilih kota tujuan</option>
    <option value="Jakarta" {{ request('kota') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
    <option value="Bali" {{ request('kota') == 'Bali' ? 'selected' : '' }}>Bali</option>
    <option value="Bandung" {{ request('kota') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
    <option value="Lombok" {{ request('kota') == 'Lombok' ? 'selected' : '' }}>Lombok</option>
    <option value="Yogyakarta" {{ request('kota') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
    <option value="Pangandaran" {{ request('kota') == 'Pangandaran' ? 'selected' : '' }}>Pangandaran</option>
    <option value="Malang" {{ request('kota') == 'Malang' ? 'selected' : '' }}>Malang</option>
    <option value="Palembang" {{ request('kota') == 'Palembang' ? 'selected' : '' }}>Palembang</option>
    <option value="Surabaya" {{ request('kota') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
    <option value="Papua" {{ request('kota') == 'Papua' ? 'selected' : '' }}>Papua</option>
    <option value="Sukabumi" {{ request('kota') == 'Sukabumi' ? 'selected' : '' }}>Sukabumi</option>
    <option value="Maluku" {{ request('kota') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
</select>
</div>

    {{-- Tanggal --}}
    <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
        <div class="d-flex align-items-center mb-1">
            <span class="fw-semibold">Tanggal</span>
        </div>
        <input type="text" class="form-control border-0 p-0 shadow-none"
               name="dates" value="{{ request('dates') }}"
               placeholder="22 Sept - 26 Sept">
    </div>

    {{-- Button --}}
    <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-3 fw-semibold">
                Search
            </button>
        </div>
    </div>
</div>
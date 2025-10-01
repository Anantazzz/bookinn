<div>
    <div class="bg-white shadow rounded-4 p-3 d-flex flex-column flex-lg-row align-items-stretch gap-3">
    <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
    <div class="d-flex align-items-center mb-1">
        <i class="bi bi-geo-alt-fill me-2"></i>
        <span class="fw-semibold">Where to?</span>
    </div>
    <select class="form-select border-0 shadow-none p-0">
        <option value="" disabled selected>Pilih kota tujuan</option>
        <option value="Jakarta">Jakarta</option>
        <option value="Bali">Bali</option>
        <option value="Bandung">Bandung</option>
        <option value="Lombok">Lombok</option>
        <option value="Yogyakarta">Yogyakarta</option>
        <option value="Pangandaran">Pangandaran</option>
        <option value="Malang">Malang</option>
        <option value="Palembang">Palembang</option>
        <option value="Surabaya">Surabaya</option>
        <option value="Papua">Papua</option>
        <option value="Sukabumi">Sukabumi</option>
        <option value="Maluku">Maluku</option>
    </select>
</div>

    {{-- Dates --}}
    <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
        <div class="d-flex align-items-center mb-1">
            <i class="bi bi-calendar-event me-2"></i>
            <span class="fw-semibold">Dates</span>
        </div>
        <input type="text" class="form-control border-0 p-0 shadow-none"
               name="dates" value="{{ request('dates') }}"
               placeholder="22 Sept - 26 Sept">
    </div>

    {{-- Travellers --}}
    <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
        <div class="d-flex align-items-center mb-1">
            <i class="bi bi-person-fill me-2"></i>
            <span class="fw-semibold">Travellers</span>
        </div>
        <input type="text" class="form-control border-0 p-0 shadow-none"
               name="travellers" value="{{ request('travellers') }}"
               placeholder="2 Travellers, 1 Room">
    </div>

    {{-- Button --}}
    <div class="d-flex align-items-center">
        <button type="submit" class="btn btn-dark rounded-3 px-4 py-3 fw-semibold">
            Search
        </button>
    </div>
</div>

</div>
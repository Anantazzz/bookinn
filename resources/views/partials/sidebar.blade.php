<div class="p-3 border rounded shadow-sm bg-white sticky-top" 
     style="top: 80px; max-height: calc(100vh - 100px); overflow-y: auto;">
     
    <h5 class="mb-4">Filter berdasarkan</h5>

    {{-- Harga per malam --}}
    <div class="mb-4">
        <label class="form-label fw-bold">Harga per malam</label>
        <div class="d-flex justify-content-between mb-2">
            <input type="text" class="form-control text-center" value="Rp400000" readonly style="max-width: 120px;">
            <input type="text" class="form-control text-center" value="Rp1000000" readonly style="max-width: 120px;">
        </div>
        <input type="range" class="form-range" min="400000" max="1000000">
    </div>

    {{-- Peringkat Bintang --}}
    <div>
        <label class="form-label fw-bold">Peringkat bintang</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="5" id="bintang5">
            <label class="form-check-label" for="bintang5">5 bintang</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="4" id="bintang4">
            <label class="form-check-label" for="bintang4">4 bintang</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="3" id="bintang3">
            <label class="form-check-label" for="bintang3">3 bintang</label>
        </div>
    </div>
</div>

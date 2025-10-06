<form action="{{ route('hotel') }}" method="GET">
    <div class="p-3 border rounded shadow-sm bg-white sticky-top" 
         style="top: 80px; max-height: calc(100vh - 100px); overflow-y: auto;">
         
        <h5 class="mb-4">Filter berdasarkan</h5>

        {{-- Harga per malam --}}
       <div class="mb-4">
         <label class="form-label fw-bold">Harga per malam</label>
            <div class="d-flex justify-content-between mb-2">
                <input type="number" class="form-control text-center" 
                    name="min_harga" 
                    value="{{ request('min_harga', 400000) }}" 
                    style="max-width: 120px;">
                <input type="number" class="form-control text-center" 
                    name="max_harga" 
                    value="{{ request('max_harga', 1000000) }}" 
                    style="max-width: 120px;">
            </div>
        </div>

        {{-- Peringkat Bintang --}}
        <div>
            <label class="form-label fw-bold">Peringkat bintang</label>
            @for($i = 5; $i >= 3; $i--)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="bintang[]" value="{{ $i }}" 
                       id="bintang{{ $i }}" {{ in_array($i, (array)request('bintang')) ? 'checked' : '' }}>
                <label class="form-check-label" for="bintang{{ $i }}">{{ $i }} bintang</label>
            </div>
            @endfor
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">Filter</button>
    </div>
</form>

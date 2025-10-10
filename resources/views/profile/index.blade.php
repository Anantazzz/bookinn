@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-center">
        <div class="card user-card shadow-sm border-primary text-center">
            <div class="card-body position-relative d-flex flex-column align-items-center justify-content-center">
                
                {{-- Tombol Edit --}}
                <button class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-3" 
                        data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    Edit
                </button>

                {{-- Avatar Placeholder --}}
                <div class="profile-img mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff&size=128" 
                         alt="Avatar" class="rounded-circle border border-3 border-primary" width="100" height="100">
                </div>

                {{-- Info User --}}
                <h4 class="fw-bold mb-2">{{ $user->name }}</h4>
                <p class="text-success mb-1">{{ $user->email }}</p>
                <p class="mb-1"><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</p>
                <p class="mb-0"><strong>No HP:</strong> {{ $user->no_hp ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Profile --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Nama</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name', $user->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Alamat</label>
                <input type="text" class="form-control @error('alamat') is-invalid @enderror" 
                       name="alamat" value="{{ old('alamat', $user->alamat) }}">
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- No HP --}}
            <div class="mb-3">
                <label class="form-label fw-bold">No HP</label>
                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                       name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- CSS tambahan --}}
<style>
.user-card {
    width: 280px; /* sempit seperti potret */
    min-height: 420px; /* tinggi biar seperti kartu */
    border: 2px solid #0d6efd;
    border-radius: 20px;
    transition: 0.3s ease;
    overflow: hidden;
    background-color: #fff;
}

.user-card:hover {
    box-shadow: 0 0 20px rgba(13, 110, 253, 0.3);
}

.profile-img img {
    object-fit: cover;
}
</style>
@endsection

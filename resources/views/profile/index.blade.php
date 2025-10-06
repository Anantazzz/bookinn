@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body position-relative">
                    {{-- Tombol Edit --}}
                    <button class="btn btn-sm btn-outline-primary position-absolute top-0 end-0 m-3" 
                            data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit
                    </button>

                    {{-- Info User --}}
                    <h4 class="text-center mb-2">{{ $user->name }}</h4>
                    <p class="text-center text-muted mb-1">{{ $user->email }}</p>
                    <p class="text-center mb-1"><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</p>
                    <p class="text-center mb-0"><strong>No HP:</strong> {{ $user->no_hp ?? '-' }}</p>
                </div>
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
@endsection

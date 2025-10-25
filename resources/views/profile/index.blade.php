@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-center">
        <div class="card profile-card border-0 shadow">
            <div class="card-body position-relative">
                
                {{-- Background Gradient Header --}}
                <div class="profile-header"></div>

                {{-- Tombol Edit --}}
                <button class="btn btn-sm btn-light position-absolute rounded-pill shadow-sm" 
                        style="top: 20px; right: 20px; z-index: 10;"
                        data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1 mb-1" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                    Edit
                </button>

                {{-- Avatar --}}
                <div class="text-center position-relative" style="margin-top: 40px;">
                    <div class="profile-avatar mx-auto position-relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff&size=200" 
                             alt="Avatar" class="rounded-circle shadow-lg border border-4 border-white" width="120" height="120">
                        <div class="avatar-ring"></div>
                    </div>
                </div>

                {{-- Info User --}}
                <div class="text-center mt-4 px-3">
                    <h4 class="fw-bold mb-1" style="color: #1a1a1a; font-size: 1.5rem;">{{ $user->name }}</h4>
                    <p class="text-primary mb-4 d-flex align-items-center justify-content-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                        </svg>
                        {{ $user->email }}
                    </p>

                    <div class="info-section">
                        <div class="info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $user->alamat ?? 'Belum diisi' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                </svg>
                            </div>
                            <div class="info-content">
                                <div class="info-label">No HP</div>
                                <div class="info-value">{{ $user->no_hp ?? 'Belum diisi' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Profile --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="editProfileLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 py-4">
            {{-- Nama --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" style="color: #1a1a1a;">Nama</label>
                <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name', $user->name) }}"
                       style="padding: 0.75rem 1rem; border: 1.5px solid #e0e0e0;">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" style="color: #1a1a1a;">Alamat</label>
                <input type="text" class="form-control rounded-3 @error('alamat') is-invalid @enderror" 
                       name="alamat" value="{{ old('alamat', $user->alamat) }}"
                       style="padding: 0.75rem 1rem; border: 1.5px solid #e0e0e0;">
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- No HP --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" style="color: #1a1a1a;">No HP</label>
                <input type="text" class="form-control rounded-3 @error('no_hp') is-invalid @enderror" 
                       name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                       style="padding: 0.75rem 1rem; border: 1.5px solid #e0e0e0;">
                @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="modal-footer border-0 pt-0 px-4 pb-4">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- CSS Modern --}}
<style>
.profile-card {
    width: 420px;
    border-radius: 24px;
    overflow: hidden;
    background: #fff;
    transition: all 0.3s ease;
}

.profile-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
}

.profile-header {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px 24px 0 0;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    position: relative;
}

.avatar-ring {
    position: absolute;
    top: -8px;
    left: -8px;
    right: -8px;
    bottom: -8px;
    border: 3px solid #667eea;
    border-radius: 50%;
    opacity: 0.3;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.3;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.1;
    }
}

.info-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 16px;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.info-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    flex-shrink: 0;
}

.info-content {
    text-align: left;
    flex: 1;
}

.info-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 0.95rem;
    color: #1a1a1a;
    font-weight: 500;
}

.modal-content {
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-control:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15) !important;
}

@media (max-width: 576px) {
    .profile-card {
        width: 100%;
        margin: 0 1rem;
    }
}
</style>
@endsection
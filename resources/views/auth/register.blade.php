@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-4 text-center">Register</h2>
                    <form>
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" class="form-control" placeholder="Masukkan nama">
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Masukkan email">
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Masukkan password">
                        </div>
                        <div class="mb-3">
                            <label>Konfirmasi Password</label>
                            <input type="password" class="form-control" placeholder="Masukkan ulang password">
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <input type="text" class="form-control" placeholder="Masukkan alamat">
                        </div>
                        <div class="mb-3">
                            <label>No HP</label>
                            <input type="text" class="form-control" placeholder="Masukkan nomor HP">
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select class="form-select">
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                                <option value="resepsionis">Resepsionis</option>
                                <option value="owner">Owner</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                        <p class="text-center mt-3">
                            Sudah punya akun? <a href="{{ url('/login') }}">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

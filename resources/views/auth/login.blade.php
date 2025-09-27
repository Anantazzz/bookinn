@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-4 text-center">Login</h2>
                    <form>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Masukkan email">
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Masukkan password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <p class="text-center mt-3">
                            Belum punya akun? <a href="{{ url('/register') }}">Register</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
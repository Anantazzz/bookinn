<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .bg-register {
            /* Ganti nama file dengan yang ada di public/images */
            background: url('{{ asset("images/hero_hotel.jpg") }}') no-repeat center center;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-register">

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center fw-semibold text-primary">Register</h4>

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No HP</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor HP">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Register</button>

                    <p class="text-center mt-3 mb-0">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>

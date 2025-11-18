<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .bg-login {
            /* Path ke gambar di public/images */
            background: url('{{ asset("images/hero_hotel.jpg") }}') no-repeat center center;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-login">

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-4">

                <h4 class="mb-4 text-center fw-semibold text-primary">Login</h4>

                {{-- Tampilkan error --}}
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" autocomplete="username">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" autocomplete="current-password" id="password">
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('password')" style="border: none; background: none; padding: 0 10px;">
                                <i class="bi bi-eye" id="password-icon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Login</button>

                    <p class="text-center mt-3 mb-0">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Register</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookInn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .navbar-nav .nav-link {
            color: #000; 
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background-color: #F7E380;
            border-radius: 6px;
            color: #000 !important;
            padding: 6px 12px;
        }
        .navbar-brand img {
            height: 40px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-2">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_bookin.png') }}" alt="BookInn Logo" class="me-2">
            <span class="fw-bold" style="font-family: 'Segoe Script', cursive;">BookInn</span>
        </a>

        {{-- Toggle Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu --}}
        <div class="collapse navbar-collapse" id="navbarNav">

            {{-- Menu Tengah --}}
            <ul class="navbar-nav gap-4 position-absolute start-50 translate-middle-x">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('hotel') ? 'active' : '' }}" href="{{ url('/hotel') }}">Hotel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('riwayat') ? 'active' : '' }}" href="{{ url('/riwayat') }}">Riwayat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ url('/profile') }}">Profile</a>
                </li>
            </ul>
            
            {{-- Menu Kanan (Login/Register) --}}
                        <ul class="navbar-nav ms-auto gap-3">
                            @guest
                                <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                                <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                            @endguest
                        </ul>

                    {{-- Logout kalau user sudah login --}}
                    @auth
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-decoration-none">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Footer --}}
        @include('partials.footer')

</body>
</html>

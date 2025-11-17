<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Resepsionis')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md py-4 px-4 md:px-8 mb-8">
        <div class="flex justify-between items-center">
            {{-- Kiri: Logo --}}
            <a href="{{ route('resepsionis.dashboard') }}" class="flex items-center gap-2 md:gap-3 flex-shrink-0 hover:opacity-80 transition">
                <i class="fa-solid fa-hotel text-blue-600 text-2xl md:text-4xl"></i>
                <img src="{{ asset('images/logo_bookin.png') }}" alt="Logo" class="h-8 md:h-14 w-auto object-contain">
            </a>

            {{-- Tengah: Sapaan (Hidden on mobile) --}}
            <div class="hidden lg:block text-xl xl:text-2xl font-semibold text-gray-800 tracking-wide">
                @php
                    $shift = strtolower(trim(Auth::user()->shift ?? 'pagi'));
                @endphp

                @if ($shift === 'siang')
                    Selamat Siang ☀️
                @elseif ($shift === 'malam')
                    Selamat Malam 🌙
                @else
                    Selamat Datang 👋
                @endif
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                <i class="fa-solid fa-bars text-xl text-gray-800"></i>
            </button>

            {{-- Desktop: Profil + Logout --}}
            <div class="hidden md:flex items-center gap-2 lg:gap-4 flex-shrink-0">
                <span class="font-medium text-gray-700 text-sm lg:text-base">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 lg:px-4 py-2 rounded-lg font-medium transition text-sm lg:text-base">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="md:hidden mt-4 pb-4 border-t border-gray-200 hidden">
            <div class="pt-4 space-y-3">
                {{-- Mobile Greeting --}}
                <div class="text-center text-lg font-semibold text-gray-800">
                    @if ($shift === 'siang')
                        Selamat Siang ☀️
                    @elseif ($shift === 'malam')
                        Selamat Malam 🌙
                    @else
                        Selamat Datang 👋
                    @endif
                </div>
                
                {{-- Mobile User Info --}}
                <div class="text-center">
                    <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>
                </div>
                
                {{-- Mobile Logout --}}
                <div class="text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    {{-- Main Content --}}
    <main class="py-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center py-4 text-gray-500 text-sm mt-10">
        &copy; {{ date('Y') }} Hotel Management System — Resepsionis Panel
    </footer>
    {{-- Mobile Menu Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    
                    // Toggle icon
                    const icon = mobileMenuBtn.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.className = 'fa-solid fa-bars text-xl text-gray-800';
                    } else {
                        icon.className = 'fa-solid fa-times text-xl text-gray-800';
                    }
                });
            }
        });
    </script>
</body>
</html>

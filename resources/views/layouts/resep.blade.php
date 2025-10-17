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
   <nav class="bg-white shadow-md py-4 px-8 flex justify-between items-center mb-8">
        {{-- Kiri: Logo --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            <i class="fa-solid fa-hotel text-blue-600 text-4xl"></i>
            <img src="{{ asset('images/logo_bookin.png') }}" alt="Logo" class="h-14 w-auto object-contain">
        </div>

        {{-- Tengah: Sapaan --}}
        <div class="absolute left-1/2 transform -translate-x-1/3 text-2xl font-semibold text-gray-800 tracking-wide">
            @php
                $shift = strtolower(trim(Auth::user()->shift ?? 'pagi'));
            @endphp

            @if ($shift === 'pagi')
                Selamat Pagi ☀️
            @elseif ($shift === 'sore')
                Selamat Sore 🌇
            @elseif ($shift === 'malam')
                Selamat Malam 🌙
            @else
                Selamat Datang 👋
            @endif
        </div>

        {{-- Kanan: Profil + Logout --}}
        <div class="flex items-center gap-4 flex-shrink-0">
            <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>
           <form method="POST" action="{{ route('logout') }}">
             @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition">
                    Logout
                </button>
            </form>
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
</body>
</html>

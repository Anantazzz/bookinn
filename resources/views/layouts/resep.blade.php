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
    {{-- Navbar --}}
    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-hotel text-blue-600 text-2xl"></i>
            <span class="text-xl font-semibold text-gray-800">Resepsionis Panel</span>
        </div>

        {{-- Info User / Logout --}}
        <div class="flex items-center gap-4">
            <span class="text-gray-700 font-medium">
                {{ Auth::user()->name ?? 'Resepsionis' }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
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

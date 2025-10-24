<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Owner')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md py-4 px-8 flex justify-between items-center mb-8 sticky top-0 z-10">
        {{-- Kiri: Logo --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            <img src="{{ asset('images/logo_bookin.png') }}" alt="Logo" class="h-12 w-auto object-contain">
            <span class="text-xl font-semibold text-gray-800 hidden sm:inline">BookInn</span>
        </div>

        {{-- Kanan: User Info --}}
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-900">Hi, {{ Auth::user()->name ?? 'Owner' }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-10 py-5 text-center text-gray-500 text-sm border-t">
        &copy; {{ date('Y') }} BookInn • Dashboard Owner
    </footer>
</body>
</html>

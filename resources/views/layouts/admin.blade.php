<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BookInn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">

    {{-- Mobile Menu Button --}}
    <button id="mobile-menu-btn" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded-lg shadow-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    {{-- Mobile Overlay --}}
    <div id="mobile-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

    <div class="flex">
        {{-- Sidebar --}}
        @include('partials.sidebar-admin')

        {{-- Main Content --}}
        <main class="flex-1 bg-gray-100 text-gray-800 p-4 lg:p-8 lg:rounded-l-2xl min-h-screen">
            @yield('content')
        </main>
    </div>

    {{-- Mobile Menu Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
            
            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        });
    </script>

</body>
</html>

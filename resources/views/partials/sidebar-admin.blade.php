<aside class="w-64 bg-gray-800 p-6 flex flex-col">
    <h2 class="text-xl font-bold mb-10">Dashboard Admin</h2>
    <nav class="flex flex-col space-y-2">
        <a href="{{ route('admin.hotels.index') }}" 
           class="px-3 py-2 rounded-md {{ request()->is('admin/hotels*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700 hover:text-white' }}">Hotel</a>
        <a href="{{ route('admin.kamars.index') }}" class="px-3 py-2 rounded-md hover:bg-gray-700 hover:text-white">Kamar</a>
        <a href="{{ route('admin.resepsionis.index') }}" class="px-3 py-2 rounded-md hover:bg-gray-700 hover:text-white">Resepsionis</a>
        <a href="{{ route('admin.owners.index') }}" class="px-3 py-2 rounded-md hover:bg-gray-700 hover:text-white">Owner</a>
    </nav>
</aside>

@php
    use App\Models\Hotel;
    $hotels = \App\Models\Hotel::all();
    $kotas = $hotels->pluck('kota')->unique(); // Ambil daftar kota unik
@endphp

<aside class="w-64 bg-gray-900 min-h-screen p-6 flex flex-col">
    <h2 class="text-2xl font-bold mb-10 text-white text-center">Admin Panel</h2>

    <nav class="flex flex-col space-y-2 text-gray-300">
        {{-- Menu Hotel + Dropdown berdasarkan kota --}}
        <div x-data="{ open: {{ request()->is('admin/hotels*') ? 'true' : 'false' }}, search: '' }" class="flex flex-col">
            <button @click="open = !open"
                class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-700 transition-all duration-200">
                <span class="flex items-center gap-3">
                    <span>Hotel</span>
                </span>
                <svg :class="open ? 'rotate-90' : ''" class="w-4 h-4 transform transition-transform duration-200"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Submenu: daftar kota + search --}}
            <div x-show="open" x-collapse class="pl-6 mt-2 space-y-1">
                {{-- Input Search Kota --}}
                <div class="relative mb-2">
                    <input type="text" x-model="search" placeholder="Cari kota..."
                        class="w-full px-3 py-2 text-sm rounded-md bg-gray-800 text-gray-200 border border-gray-700 focus:outline-none focus:ring focus:ring-blue-500">
                    <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.9-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z" /></svg>
                </div>

                {{-- Filter kota berdasarkan pencarian --}}
                <template x-for="kota in {{ $kotas->values()->toJson() }}" :key="kota">
                    <template x-if="kota.toLowerCase().includes(search.toLowerCase())">
                        <a :href="'{{ route('admin.hotels.index') }}?kota=' + encodeURIComponent(kota)"
                           class="block px-3 py-1.5 rounded-md text-sm text-gray-300 hover:bg-gray-700 hover:text-white"
                           :class="{ 'bg-gray-700 text-white font-semibold': '{{ request('kota') }}' == kota }"
                           x-text="kota">
                        </a>
                    </template>
                </template>
            </div>
        </div>

        {{-- Menu Kamar + Dropdown berdasarkan hotel --}}
        <div x-data="{ open: {{ request()->is('admin/kamars*') ? 'true' : 'false' }}, search: '' }" class="flex flex-col">
            <button @click="open = !open"
                class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-700 transition-all duration-200">
                <span class="flex items-center gap-3">
                    <span>Kamar</span>
                </span>
                <svg :class="open ? 'rotate-90' : ''" class="w-4 h-4 transform transition-transform duration-200"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Submenu: daftar hotel + search --}}
            <div x-show="open" x-collapse class="pl-6 mt-2 space-y-1">
                <div class="relative mb-2">
                    <input type="text" x-model="search" placeholder="Cari hotel..."
                        class="w-full px-3 py-2 text-sm rounded-md bg-gray-800 text-gray-200 border border-gray-700 focus:outline-none focus:ring focus:ring-blue-500">
                    <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.9-5.4a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z" /></svg>
                </div>

                <template x-for="hotel in {{ $hotels->toJson() }}" :key="hotel.id">
                    <template x-if="hotel.nama_hotel.toLowerCase().includes(search.toLowerCase())">
                        <a :href="'{{ route('admin.kamars.index') }}?hotel_id=' + hotel.id"
                           class="block px-3 py-1.5 rounded-md text-sm text-gray-300 hover:bg-gray-700 hover:text-white"
                           :class="{ 'bg-gray-700 text-white font-semibold': '{{ request('hotel_id') }}' == hotel.id }"
                           x-text="hotel.nama_hotel">
                        </a>
                    </template>
                </template>
            </div>
        </div>

        {{-- Menu Resepsionis --}}
        <a href="{{ route('admin.resepsionis.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 
                  {{ request()->is('admin/resepsionis*') ? 'bg-gray-700 text-white font-semibold' : 'hover:bg-gray-700 hover:text-white' }}">
            <span>Resepsionis</span>
        </a>

        {{-- Menu Owner --}}
        <a href="{{ route('admin.owners.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 
                  {{ request()->is('admin/owners*') ? 'bg-gray-700 text-white font-semibold' : 'hover:bg-gray-700 hover:text-white' }}">
            <span>Owner</span>
        </a>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-8">
            @csrf
            <button type="submit" 
                class="block w-full text-left px-4 py-2 rounded-md hover:bg-red-600 hover:text-white transition">
                Logout
            </button>
        </form>
    </nav>
</aside>

<script src="//unpkg.com/alpinejs" defer></script>

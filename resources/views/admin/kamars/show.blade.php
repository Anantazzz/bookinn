@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('admin.kamars.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                <span class="font-semibold">Kembali ke Daftar Kamar</span>
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            {{-- Hero Image Section --}}
            <div class="relative h-96 overflow-hidden">
                @if($kamar->gambar)
                    <img src="{{ asset('images/kamars/' . $kamar->gambar) }}" 
                         alt="Kamar {{ $kamar->nomor_kamar }}" 
                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-gray-500" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                        </svg>
                    </div>
                @endif
                
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                
                {{-- Room Number Overlay --}}
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <div class="flex items-end justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">
                                Kamar {{ $kamar->nomor_kamar }}
                            </h1>
                            <div class="flex items-center gap-2 text-white/90">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                                </svg>
                                <span class="text-lg font-semibold">{{ $kamar->hotel->nama_hotel }}</span>
                            </div>
                        </div>
                        
                        {{-- Status Badge --}}
                        <div class="bg-white/95 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-xl">
                            @if($kamar->status == 'tersedia')
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                    <span class="text-xl font-bold text-green-600">Tersedia</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                    <span class="text-xl font-bold text-orange-600">Booking</span>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 text-center mt-1">Status</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="p-8">
                
                {{-- Info Grid --}}
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    
                    {{-- Hotel Card --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama Hotel</p>
                                <p class="text-xl font-bold text-gray-900">{{ $kamar->hotel->nama_hotel }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Room Type Card --}}
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v7A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 13.5 3h-11zm0 1h11a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipe Kamar</p>
                                <p class="text-xl font-bold text-gray-900">{{ $kamar->tipeKamar->nama_tipe ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Room Number Card --}}
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6 1H1v14h5V1zm9 0h-5v5h5V1zm0 9v5h-5v-5h5zM0 1a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1zm9 0a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1V1zm1 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1h-5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Kamar</p>
                                <p class="text-xl font-bold text-gray-900">{{ $kamar->nomor_kamar }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Price Card --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl p-6 border border-yellow-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Harga per Malam</p>
                                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Capacity Card --}}
                    <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Kapasitas</p>
                                <p class="text-xl font-bold text-gray-900">{{ $kamar->kapasitas }} Orang</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bed Card --}}
                    <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-2xl p-6 border border-rose-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3 12h10V5H3v7zm0 1a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H3zm-1 2h12v1H2v-1z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Jumlah Bed</p>
                                <p class="text-xl font-bold text-gray-900">{{ $kamar->jumlah_bed }} Tempat Tidur</p>
                            </div>
                        </div>
                    </div>

                    {{-- Internet Card --}}
                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-2xl p-6 border border-violet-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z"/>
                                    <path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Fasilitas Internet</p>
                                <p class="text-xl font-bold text-gray-900">{{ $kamar->internet ? 'WiFi Tersedia' : 'Tidak Ada WiFi' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Status Card --}}
                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-2xl p-6 border border-slate-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Kamar</p>
                                <p class="text-xl font-bold text-gray-900">{{ ucfirst($kamar->status) }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.kamars.index') }}" 
                       class="flex-1 min-w-[200px] inline-flex items-center justify-center gap-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-4 rounded-xl transition-all hover:shadow-lg hover:scale-105 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
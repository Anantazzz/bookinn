@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 sm:py-8 px-3 sm:px-4">
  <div class="max-w-7xl mx-auto">
      
      {{-- Header Section --}}
      <div class="mb-6 sm:mb-8">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div>
                  <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 flex items-center gap-2 sm:gap-3">
                      <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 16 16">
                              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                          </svg>
                      </div>
                      <span class="break-words">Data Customer</span>
                  </h1>
              </div>
          </div>
      </div>

      {{-- Table Card - Desktop View --}}
      <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden">
          <div class="overflow-x-auto">
              <table class="min-w-full">
                  <thead>
                      <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No HP</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Reservasi</th>
                          <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Terdaftar</th>
                          <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                      @forelse ($customers as $index => $customer)
                          <tr class="hover:bg-gray-50 transition-colors duration-150">
                              <td class="py-4 px-6 font-semibold text-gray-800">{{ $customer->name }}</td>
                              <td class="py-4 px-6 text-sm text-gray-600">{{ $customer->email }}</td>
                              <td class="py-4 px-6 text-gray-600">{{ $customer->no_hp ?? '-' }}</td>
                              <td class="py-4 px-6 text-sm text-gray-600">{{ Str::limit($customer->alamat ?? '-', 30) }}</td>
                              <td class="py-4 px-6">
                                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                      {{ $customer->reservasis_count }}
                                  </span>
                              </td>
                              <td class="py-4 px-6 text-gray-600">{{ $customer->created_at->format('d/m/Y') }}</td>
                              <td class="py-4 px-6 text-center">
                                  <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                      class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-semibold text-sm">
                                      Detail
                                  </a>
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="7" class="text-center py-12">
                                  <div class="flex flex-col items-center gap-3">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                                          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                      </svg>
                                      <p class="text-gray-500 font-semibold text-lg">Belum ada data customer</p>
                                      <p class="text-gray-400 text-sm">Customer akan muncul setelah melakukan registrasi</p>
                                  </div>
                              </td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>

      {{-- Card View - Mobile & Tablet --}}
      <div class="lg:hidden space-y-4">
          @forelse ($customers as $index => $customer)
              <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                  <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                      <div class="flex items-center justify-between">
                          <span class="text-sm font-bold text-gray-700">Customer #{{ $index + 1 }}</span>
                          <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-semibold">
                              {{ $customer->reservasis_count }} Reservasi
                          </span>
                      </div>
                  </div>
                  <div class="p-4 space-y-3">
                      <div>
                          <p class="text-xs text-gray-500 mb-1">Nama</p>
                          <p class="font-semibold text-gray-800">{{ $customer->name }}</p>
                      </div>
                      <div>
                          <p class="text-xs text-gray-500 mb-1">Email</p>
                          <p class="text-sm text-gray-600 break-all">{{ $customer->email }}</p>
                      </div>
                      <div class="grid grid-cols-2 gap-3">
                          <div>
                              <p class="text-xs text-gray-500 mb-1">No HP</p>
                              <p class="text-sm text-gray-600">{{ $customer->no_hp ?? '-' }}</p>
                          </div>
                          <div>
                              <p class="text-xs text-gray-500 mb-1">Terdaftar</p>
                              <p class="text-sm text-gray-600">{{ $customer->created_at->format('d/m/Y') }}</p>
                          </div>
                      </div>
                      <div>
                          <p class="text-xs text-gray-500 mb-1">Alamat</p>
                          <p class="text-sm text-gray-600">{{ $customer->alamat ?? '-' }}</p>
                      </div>
                      <div class="pt-3">
                          <a href="{{ route('admin.customers.show', $customer->id) }}" 
                              class="w-full bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all duration-200 font-semibold text-sm text-center block">
                              Detail
                          </a>
                      </div>
                  </div>
              </div>
          @empty
              <div class="bg-white rounded-xl shadow-lg p-8">
                  <div class="flex flex-col items-center gap-3">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                      </svg>
                      <p class="text-gray-500 font-semibold text-base sm:text-lg text-center">Belum ada data customer</p>
                      <p class="text-gray-400 text-xs sm:text-sm text-center">Customer akan muncul setelah melakukan registrasi</p>
                  </div>
              </div>
          @endforelse
      </div>
      
      {{-- Pagination --}}
      @if($customers->hasPages())
          <div class="mt-6">
              {{ $customers->links() }}
          </div>
      @endif
  </div>
</div>
@endsection
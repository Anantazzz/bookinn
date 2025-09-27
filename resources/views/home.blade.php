@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <section class="position-relative rounded-4 overflow-hidden">
        {{-- Background Image --}}
        <img src="{{ asset('images/hero_hotel.jpg') }}" 
             alt="Hero Background" 
             class="w-100" 
             style="height: 420px; object-fit: cover;">

        {{-- Search Box --}}
        <div class="position-absolute top-50 start-50 translate-middle w-75">
            <div class="bg-white shadow rounded-4 p-4 d-flex flex-column flex-lg-row align-items-stretch gap-3">
                
                {{-- Where to --}}
                <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        <span class="fw-semibold">Where to?</span>
                    </div>
                    <input type="text" class="form-control border-0 p-0 shadow-none" 
                           placeholder="Jakarta, Special Capital Region...">
                </div>

                {{-- Dates --}}
                <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-calendar-event me-2"></i>
                        <span class="fw-semibold">Dates</span>
                    </div>
                    <input type="text" class="form-control border-0 p-0 shadow-none" 
                           placeholder="22 Sept - 26 Sept">
                </div>

                {{-- Travellers --}}
                <div class="flex-fill border rounded-3 p-3 d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-person-fill me-2"></i>
                        <span class="fw-semibold">Travellers</span>
                    </div>
                    <input type="text" class="form-control border-0 p-0 shadow-none" 
                           placeholder="2 Travellers, 1 Room">
                </div>

                {{-- Button --}}
                <div class="d-flex align-items-center">
                    <button class="btn btn-dark rounded-3 px-4 py-3 fw-semibold">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection

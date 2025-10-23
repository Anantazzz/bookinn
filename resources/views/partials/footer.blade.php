<footer class="text-white py-5" style="background-color: #2B2B2B;">
    <div class="container">
        <div class="row">
            
            {{-- Kolom kiri --}}
            <div class="col-md-6 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/logo_bookin.png') }}" alt="bookInn" style="height: 60px;">
                    <span class="ms-2 fw-semibold fs-5">Book'Inn</span>
                </div>
                <p class="mb-0" style="line-height: 1.6;">
                    Temukan hotel impian Anda dengan mudah dan cepat. <br>
                    Kami menyediakan berbagai pilihan akomodasi dengan <br>
                    harga bersahabat dan layanan terpercaya.
                </p>
            </div>

            {{-- Kolom kanan --}}
            <div class="col-md-6">
                <div class="row">
                    
                    {{-- Our Hotels --}}
                    <div class="col-6 col-md-4 mb-3">
                        <h6 class="fw-semibold mb-3">Our hotels</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white text-decoration-none">Jakarta</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Bali</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Bandung</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Lombok</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Yogyakarta</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Pangandaran</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Malang</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Palembang</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Surabaya</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Papua</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Sukabumi</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Maluku</a></li>
                        </ul>
                    </div>

                    {{-- Menu --}}
                    <div class="col-6 col-md-4 mb-3">
                        <h6 class="fw-semibold mb-3">Menu</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route("home") }}" class="text-white text-decoration-none">Beranda</a></li>
                            <li><a href="{{ route("hotel") }}" class="text-white text-decoration-none">Hotel</a></li>
                            <li><a href="{{ route("riwayat") }}" class="text-white text-decoration-none">Riwayat</a></li>
                            <li><a href="{{ route("profile") }}" class="text-white text-decoration-none">Profile</a></li>
                        </ul>
                    </div>

                    {{-- Bantuan --}}
                    <div class="col-6 col-md-4 mb-3">
                        <h6 class="fw-semibold mb-3">Sosial Media</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white text-decoration-none">Instagram @bookinn</a></li>
                            <li><a href="#" class="text-white text-decoration-none">TikTok @bookinn</a></li>
                            <li><a href="#" class="text-white text-decoration-none">Fb @bookinn</a></li>
                            <li><a href="#" class="text-white text-decoration-none">X @bookinn</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="text-center pt-4 mt-4 border-top: 2px solid #007bff;">
            <small>© 2025 Book'Inn. All Rights Reserved</small>
        </div>
    </div>
</footer>

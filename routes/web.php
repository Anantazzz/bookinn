<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProfileController,
    HomeController,
    HotelController,
    ReservasiController,
    PembayaranController,
    InvoiceController,
    AdminHotelController,
    AdminKamarController,
    AdminResepsionisController,
    AdminOwnerController,
    DashResepsionisController,
    ResepKamarController,
    ResepCheckController,
    ResepInvoiceController,
    RiwayatController,
    PembatalanController,
    DashOwnerController,
};

//PUBLIC (Tanpa Login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hotel', [HotelController::class, 'index'])->name('hotel');
Route::get('/hotel/{id}', [HotelController::class, 'detail'])->name('hotel.detail');

//Auth
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//PROFILE (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

//RESERVASI
Route::middleware('auth')->group(function () {
    Route::get('/reservasi/{id}', [ReservasiController::class, 'showForm'])->name('hotel.reservasi');
    Route::post('/reservasi/{id}', [ReservasiController::class, 'store'])->name('reservasi.store');
});

//PEMBAYARAN
Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('hotel.pembayaran');
Route::post('/pembayaran/{id}', [PembayaranController::class, 'prosesPembayaran'])->name('hotel.prosesPembayaran');
Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');

//Riwayat
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat', [RiwayatController::class, 'riwayat'])->name('riwayat');
});

// Pembatalan
Route::middleware(['auth'])->group(function () {
    Route::get('/pembatalan/struk/{id}', [PembatalanController::class, 'show'])->name('pembatalan.show');
    Route::get('/pembatalan/{reservasi_id}', [PembatalanController::class, 'form'])->name('pembatalan.form');
    Route::post('/pembatalan/{reservasi_id}', [PembatalanController::class, 'store'])->name('pembatalan.store');
});

//ADMIN (auth + role:admin)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminHotelController::class, 'index'])->name('admin.dashboard');

    // CRUD Hotel
    Route::resource('hotels', AdminHotelController::class)->names('admin.hotels');

    // CRUD Kamar
    Route::resource('kamars', AdminKamarController::class)->names('admin.kamars');

    // CRUD Resepsionis
    Route::resource('resepsionis', AdminResepsionisController::class)->names('admin.resepsionis');

    // CRUD Owner
    Route::resource('owners', AdminOwnerController::class)->names('admin.owners');
});

Route::middleware(['auth', 'role:resepsionis'])->prefix('resepsionis')->group(function () {
    // Dashboard Resepsionis
    Route::get('/dashboard', [DashResepsionisController::class, 'index'])->name('resepsionis.dashboard');

    // Kelola Kamar
    Route::get('/kamars', [ResepKamarController::class, 'index'])->name('resepsionis.kamars.index');
    Route::put('/kamars/{id}', [ResepKamarController::class, 'update'])->name('resepsionis.kamars.update');

    // Kelola Check
    Route::get('/check', [ResepCheckController::class, 'index'])->name('resepsionis.check.index');
    Route::put('/check/{id}/status', [ResepCheckController::class, 'updateStatus'])->name('resepsionis.check.updateStatus');

    // Kelola & Cetak Tagihan
    Route::get('/invoice', [ResepInvoiceController::class, 'index'])->name('resepsionis.invoice.index');
    Route::post('/invoice/{id}/accept', [ResepInvoiceController::class, 'accept'])->name('resepsionis.invoice.accept');
    Route::get('/invoice/{id}/print', [ResepInvoiceController::class, 'print'])->name('resepsionis.invoice.print');
});

Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [DashOwnerController::class, 'index'])->name('owner.dashboard');
});
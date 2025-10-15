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
};

//PUBLIC (Tanpa Login)
Route::get('/hotel', [HotelController::class, 'index'])->name('hotel');
Route::get('/hotel/{id}', [HotelController::class, 'detail'])->name('hotel.detail');

//Hanya Customer
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

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

//RESERVASI & PEMBAYARAN
Route::middleware('auth')->group(function () {
    Route::get('/reservasi/{id}', [ReservasiController::class, 'showForm'])->name('hotel.reservasi');
    Route::post('/reservasi/{id}', [ReservasiController::class, 'store'])->name('reservasi.store');
});

Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('hotel.pembayaran');
Route::post('/pembayaran/{id}', [PembayaranController::class, 'prosesPembayaran'])->name('hotel.prosesPembayaran');
Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');

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
});



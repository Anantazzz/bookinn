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
    ResepOfflineController,
    InvoiceOfflineController
};

//PUBLIC (Tanpa Login) - Hanya untuk guest atau user
Route::middleware(['guest_or_customer'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/hotel', [HotelController::class, 'index'])->name('hotel');
    Route::get('/hotel/{id}', [HotelController::class, 'detail'])->name('hotel.detail');
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

//HOTEL
Route::get('/hotel', [HotelController::class, 'index'])->name('hotel');
Route::get('/hotel/{id}', [HotelController::class, 'detail'])->name('hotel.detail');

//USER (auth + role:user)
Route::middleware(['auth', 'role:user'])->group(function () {
    // Reservasi
    Route::get('/reservasi/{id}', [ReservasiController::class, 'showForm'])->name('hotel.reservasi');
    Route::post('/reservasi/{id}', [ReservasiController::class, 'store'])->name('reservasi.store');
    
    // Pembayaran
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('hotel.pembayaran');
    Route::post('/pembayaran/{id}', [PembayaranController::class, 'prosesPembayaran'])->name('hotel.prosesPembayaran');
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');
    
    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'riwayat'])->name('riwayat');
    
    // Pembatalan
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

//RESEPSIONIS (auth + role:resepsionis)
Route::middleware(['auth', 'role:resepsionis'])->prefix('resepsionis')->group(function () {
    // Dashboard Resepsionis
    Route::get('/dashboard', [DashResepsionisController::class, 'index'])->name('resepsionis.dashboard');

    // Kelola Kamar
    Route::get('/kamars', [ResepKamarController::class, 'index'])->name('resepsionis.kamars.index');
    Route::put('/kamars/{id}', [ResepKamarController::class, 'update'])->name('resepsionis.kamars.update');

    // Kelola Check
    Route::get('/check', [ResepCheckController::class, 'index'])->name('resepsionis.check.index');
    Route::put('/check/{id}/status', [ResepCheckController::class, 'updateStatus'])->name('resepsionis.check.updateStatus');

    // Reservasi Offline
    Route::prefix('offline')->group(function () {
        Route::get('/create', [ResepOfflineController::class, 'create'])->name('resepsionis.offline.create');
        Route::post('/store', [ResepOfflineController::class, 'store'])->name('resepsionis.offline.store'); 
        Route::get('/invoice/{id}', [ResepOfflineController::class, 'invoice'])->name('resepsionis.offline.invoice');

    // Kelola & Cetak Tagihan
    Route::get('/invoice', [ResepInvoiceController::class, 'index'])->name('resepsionis.invoice.index');
    Route::post('/invoice/{id}/accept', [ResepInvoiceController::class, 'accept'])->name('resepsionis.invoice.accept');
    Route::post('/invoice/{id}/reject', [ResepInvoiceController::class, 'reject'])->name('resepsionis.invoice.reject');
    Route::get('/invoice/{id}/print', [ResepInvoiceController::class, 'print'])->name('resepsionis.invoice.print');
    });
});

//OWNER (auth + role:owner)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [DashOwnerController::class, 'index'])->name('owner.dashboard');
});
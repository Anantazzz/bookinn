<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\InvoiceController;

use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminKamarController;
use App\Http\Controllers\AdminResepsionisController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

                            // Route User
Route::get('/', [HomeController::class, 'index'])->name('home');

//auth
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//Profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

//hotel
Route::get('/hotel', [HotelController::class, 'index'])->name('hotel');
Route::get('/hotel/{id}', [HotelController::class, 'detail'])->name('hotel.detail');

//Reservasi 
Route::get('/reservasi/{id}', [ReservasiController::class, 'showForm'])->name('hotel.reservasi')->middleware('auth');
Route::post('/reservasi/{id}', [ReservasiController::class, 'store'])->name('reservasi.store');

//Pembayaran
Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('hotel.pembayaran');
Route::post('/pembayaran/{id}', [PembayaranController::class, 'prosesPembayaran'])->name('hotel.prosesPembayaran');

//Invoice
Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');

                            // Route Admin
Route::prefix('admin')->group(function () {
    // Dashboard admin — ambil data hotel juga
    Route::get('/dashboard', [AdminHotelController::class, 'index'])->name('admin.dashboard');
    // CRUD Hotel
    Route::get('/hotels', [AdminHotelController::class, 'index'])->name('admin.hotels.index');
    Route::get('/hotels/create', [AdminHotelController::class, 'create'])->name('admin.hotels.create');
    Route::post('/hotels', [AdminHotelController::class, 'store'])->name('admin.hotels.store');
    Route::get('/hotels/{id}', [AdminHotelController::class, 'show'])->name('admin.hotels.show');
    Route::get('/hotels/{id}/edit', [AdminHotelController::class, 'edit'])->name('admin.hotels.edit');
    Route::put('/hotels/{id}', [AdminHotelController::class, 'update'])->name('admin.hotels.update');
    Route::delete('/hotels/{id}', [AdminHotelController::class, 'destroy'])->name('admin.hotels.destroy');
});
     // ROUTE ADMIN KAMAR
Route::prefix('admin')->group(function () {
        // CRUD Kamar
    Route::get('/kamars', [AdminKamarController::class, 'index'])->name('admin.kamars.index');
    Route::get('/kamars/create', [AdminKamarController::class, 'create'])->name('admin.kamars.create');
    Route::post('/kamars', [AdminKamarController::class, 'store'])->name('admin.kamars.store');
    Route::get('/kamars/{id}', [AdminKamarController::class, 'show'])->name('admin.kamars.show');
    Route::get('/kamars/{id}/edit', [AdminKamarController::class, 'edit'])->name('admin.kamars.edit');
    Route::put('/kamars/{id}', [AdminKamarController::class, 'update'])->name('admin.kamars.update');
    Route::delete('/kamars/{id}', [AdminKamarController::class, 'destroy'])->name('admin.kamars.destroy');
    
});

    // ROUTE ADMIN RESEPSIONIS
Route::prefix('admin')->group(function () {
            // CRUD Resepsionis
    Route::get('/resepsionis', [AdminResepsionisController::class, 'index'])->name('admin.resepsionis.index');
    Route::get('/resepsionis/create', [AdminResepsionisController::class, 'create'])->name('admin.resepsionis.create');
    Route::post('/resepsionis', [AdminResepsionisController::class, 'store'])->name('admin.resepsionis.store');
    Route::get('/resepsionis/{id}', [AdminResepsionisController::class, 'show'])->name('admin.resepsionis.show');
    Route::get('/resepsionis/{id}/edit', [AdminResepsionisController::class, 'edit'])->name('admin.resepsionis.edit');
    Route::put('/resepsionis/{id}', [AdminResepsionisController::class, 'update'])->name('admin.resepsionis.update');
    Route::delete('/resepsionis/{id}', [AdminResepsionisController::class, 'destroy'])->name('admin.resepsionis.destroy');
});
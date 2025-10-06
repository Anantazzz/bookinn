<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\InvoiceController;


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

//Booking & Payment
Route::get('/reservasi/{id}', [ReservasiController::class, 'showForm'])->name('hotel.reservasi')->middleware('auth');
Route::post('/reservasi/{id}', [ReservasiController::class, 'store'])->name('reservasi.store');

Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('hotel.pembayaran');
Route::post('/pembayaran/{id}', [PembayaranController::class, 'prosesPembayaran'])->name('hotel.prosesPembayaran');

//Invoice
Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');
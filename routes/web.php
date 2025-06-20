<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Livewire\ProductPage;

// RUTE PUBLIK (Bisa diakses tanpa login)
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/produk', ProductPage::class)->name('produk.index');

// RUTE KHUSUS PENGGUNA TERDAFTAR
Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman ini yang akan dilihat pengguna setelah login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Halaman profil tetap di sini karena butuh login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/account', function () {
        return view('profile.account');
    })->name('profile.account');

    //Route untuk mengelola alamat
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::get('/profile/addresses/create', [ProfileController::class, 'createAddress'])->name('profile.addresses.create');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::get('/profile/addresses/{address}/edit', [ProfileController::class, 'editAddress'])->name('profile.addresses.edit');
    Route::patch('/profile/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('profile.addresses.update');


});

require __DIR__.'/auth.php';

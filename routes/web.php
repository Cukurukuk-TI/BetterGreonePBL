<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Livewire\ProductPage;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

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
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');

    // Route untuk menambahkan produk ke keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'destroy'])->name('cart.remove');

    // Route Pesanan
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/orders', [CheckoutController::class, 'store'])->name('orders.store'); // <-- ROUTE BARU
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('order.success');

});

require __DIR__.'/auth.php';

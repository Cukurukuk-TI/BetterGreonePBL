<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// RUTE PUBLIK (Bisa diakses tanpa login)
Route::get('/', function () {
    return view('home');
})->name('home');

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
});

require __DIR__.'/auth.php';

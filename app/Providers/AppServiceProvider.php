<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Tambahkan blok kode View Composer di sini
        View::composer('layouts.navigation', function ($view) {
            // Hanya jalankan jika pengguna sudah login
            if (Auth::check()) {
                // Hitung jumlah item unik di keranjang milik pengguna
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            } else {
                $cartCount = 0;
            }

            // Kirim variabel $cartCount ke view
            $view->with('cartCount', $cartCount);
        });
    }
}

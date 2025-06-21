<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        // Ambil semua item keranjang milik pengguna yang sedang login
        // 'with('product')' digunakan untuk eager loading agar lebih efisien
        $cartItems = Cart::with('product.category')
                         ->where('user_id', auth()->id())
                         ->get();

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        // Cari item di keranjang berdasarkan user_id dan product_id
        $cartItem = Cart::where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->first();

        // Jika produk sudah ada di keranjang, tambahkan kuantitasnya
        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            // Jika produk belum ada, buat item keranjang baru
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
}

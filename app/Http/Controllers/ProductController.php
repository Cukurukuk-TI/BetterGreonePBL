<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{

    public function index(): View
    {
        // Ambil semua produk dari database, urutkan dari yang terbaru
        $products = Product::latest()->paginate(12); // paginate(12) untuk 12 produk per halaman

        // Kirim data produk ke view 'produk.index'
        return view('produk.index', [
            'products' => $products,
        ]);
    }

    public function show(Product $product): View
    {
        // Karena Route Model Binding, Laravel sudah otomatis mencarikan
        // produk untuk kita. Kita tinggal mengirimkannya ke view.
        return view('produk.show', [
            'product' => $product
        ]);
    }

}

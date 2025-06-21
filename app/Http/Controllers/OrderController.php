<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman riwayat pesanan pengguna.
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->latest() // Urutkan dari yang paling baru
                       ->paginate(10); // Gunakan paginasi jika pesanan banyak

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan halaman konfirmasi pesanan berhasil.
     */
    public function success(Order $order)
    {
        // Otorisasi: Pastikan pengguna yang mengakses adalah pemilik pesanan
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        // Tampilkan view 'order.success' dan kirim data pesanan
        return view('orders.success', compact('order'));
    }

    public function show(Order $order)
    {
        // Otorisasi: Pastikan pengguna yang mengakses adalah pemilik pesanan
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        // Eager load relasi orderItems beserta produk di dalamnya
        $order->load('orderItems.product');

        // Tampilkan view 'orders.show' dan kirim data pesanan
        return view('orders.show', compact('order'));
    }

}

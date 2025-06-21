<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        // Ambil semua item di keranjang milik pengguna yang sedang login
        $user = auth()->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Jika keranjang kosong, jangan biarkan masuk ke halaman checkout
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan belanja terlebih dahulu.');
        }

        $addresses = Address::where('user_id', $user->id)->latest()->get();

        // Tampilkan view checkout dan kirim data keranjang
        return view('checkout.index', compact('cartItems', 'addresses'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = auth()->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        $selectedAddress = Address::find($request->address_id);

        // Pastikan alamat milik user yang sedang login
        if ($selectedAddress->user_id !== $user->id) {
            return back()->with('error', 'Alamat tidak valid.');
        }

        // 2. Gunakan Database Transaction
        try {
            DB::beginTransaction();

            // Hitung total
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $shippingCost = 15000; // Nanti bisa dibuat dinamis
            $grandTotal = $subtotal + $shippingCost;

            // 3. Buat entri di tabel 'orders'
            $order = Order::create([
                'user_id' => $user->id,
                'grand_total' => $grandTotal,
                'shipping_address' => $selectedAddress->full_address,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // 4. Pindahkan item dari keranjang ke 'order_items'
            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price, // Simpan harga saat ini
                ]);
            }

            // 5. Kosongkan keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // TODO: Di commit 6, kita akan arahkan ke halaman sukses
            // Untuk sekarang, kita arahkan ke dashboard dengan pesan sukses
            return redirect()->route('dashboard')->with('success', 'Pesanan Anda berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Tampilkan error jika transaksi gagal
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
    }

}

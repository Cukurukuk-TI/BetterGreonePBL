<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        // 1. Validasi Input dari Form
        $validated = $request->validate([
            'shipping_method' => ['required', Rule::in(['delivery', 'pickup'])],
            'payment_method' => ['required', Rule::in(['cod', 'transfer'])],
            // Alamat hanya wajib jika metode pengiriman adalah 'delivery'
            'address_id' => [
                Rule::requiredIf($request->input('shipping_method') === 'delivery'),
                'exists:addresses,id'
            ],
            // Ambil data total dari hidden input
            'shipping_cost' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);

        $user = auth()->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Jika metode pembayaran adalah transfer, arahkan ke Midtrans (logika nanti)
        if ($validated['payment_method'] === 'transfer') {
            // TODO: Panggil logika untuk generate Snap Token Midtrans di sini
            return redirect()->route('home')->with('info', 'Metode pembayaran online akan segera hadir.');
        }

        // --- Proses untuk COD (Cash On Delivery) ---

        // Pengecekan stok
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok untuk produk "' . $item->product->name . '" tidak mencukupi.');
            }
        }

        // Mulai Database Transaction
        try {
            DB::beginTransaction();

            $shippingAddress = null;
            if ($validated['shipping_method'] === 'delivery') {
                $address = Address::find($validated['address_id']);
                // Pastikan alamat milik user
                if ($address->user_id !== $user->id) {
                    throw new \Exception('Alamat tidak valid.');
                }
                // Format alamat untuk disimpan di pesanan
                $shippingAddress = "{$address->recipient_name} ({$address->phone_number})\n{$address->full_address}";
            } else {
                // Jika ambil di tempat, alamat bisa dikosongkan atau diisi info toko
                $shippingAddress = "Ambil di Tempat (Toko Sayur Hidroponik)";
            }

            // Buat entri di tabel 'orders'
            $order = Order::create([
                'user_id' => $user->id,
                'grand_total' => $validated['grand_total'],
                'shipping_address' => $shippingAddress,
                'shipping_method' => $validated['shipping_method'],
                'shipping_cost' => $validated['shipping_cost'],
                'status' => 'processing', // Untuk COD, bisa langsung dianggap 'processing'
                'payment_status' => 'pending', // Status pembayaran tetap 'pending' sampai dibayar
                'payment_gateway' => 'COD',
            ]);

            // Pindahkan item dari keranjang ke 'order_items'
            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
                // Kurangi stok produk
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Kosongkan keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Arahkan ke halaman sukses
            return redirect()->route('order.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            // Tampilkan error jika transaksi gagal
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda: ' . $e->getMessage());
        }
    }

}

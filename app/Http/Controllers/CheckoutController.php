<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan belanja terlebih dahulu.');
        }

        $addresses = Address::where('user_id', $user->id)->latest()->get();
        return view('checkout.index', compact('cartItems', 'addresses'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'shipping_method' => ['required', Rule::in(['delivery', 'pickup'])],
            'payment_method' => ['required', Rule::in(['cod', 'transfer'])],
            'address_id' => [
                Rule::requiredIf($request->input('shipping_method') === 'delivery'),
                'nullable',
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
        ]);

        $user = auth()->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        $shippingAddress = ($validated['shipping_method'] === 'delivery')
            ? Address::find($validated['address_id'])->full_address
            : 'Pickup di Toko';

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Logika untuk Cash on Delivery (COD)
            if ($validated['payment_method'] === 'cod') {
                $order = Order::create([
                    'user_id' => $user->id,
                    'grand_total' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
                    'shipping_address' => $shippingAddress,
                    'status' => 'processing',
                    'payment_status' => 'pending',
                    'payment_gateway' => 'COD',
                ]);

                foreach ($cartItems as $cartItem) {
                    $order->orderItems()->create([
                        'product_id' => $cartItem->product_id, 'quantity' => $cartItem->quantity, 'price' => $cartItem->product->price,
                    ]);
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                Cart::where('user_id', $user->id)->delete();
                DB::commit();
                return redirect()->route('order.success', $order);
            }

            // ====================================================================
            // LOGIKA UNTUK MIDTRANS (TRANSFER) DIMULAI DI SINI
            // ====================================================================
            elseif ($validated['payment_method'] === 'transfer') {
                // a. Buat order dengan status 'pending'
                $order = Order::create([
                    'user_id' => $user->id,
                    'grand_total' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
                    'shipping_address' => $shippingAddress,
                    'status' => 'pending', // Status order pending karena menunggu pembayaran
                    'payment_status' => 'pending',
                    'payment_gateway' => 'Midtrans',
                ]);

                $itemDetails = [];
                foreach ($cartItems as $cartItem) {
                    $order->orderItems()->create([
                        'product_id' => $cartItem->product_id, 'quantity' => $cartItem->quantity, 'price' => $cartItem->product->price,
                    ]);
                    // Jangan kurangi stok dulu, tunggu pembayaran berhasil
                    $itemDetails[] = ['id' => $cartItem->product_id, 'price' => $cartItem->product->price, 'quantity' => $cartItem->quantity, 'name' => $cartItem->product->name];
                }

                // b. Konfigurasi Midtrans
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production');
                Config::$isSanitized = true; Config::$is3ds = true;

                // c. Siapkan parameter untuk Midtrans
                $params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $order->id . '-' . time(),
                        'gross_amount' => $order->grand_total,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name, 'email' => $user->email, 'phone' => $user->phone ?? '081234567890',
                    ],
                    'item_details' => $itemDetails,
                ];

                // d. Dapatkan Snap Token
                $snapToken = Snap::getSnapToken($params);

                // e. Simpan Snap Token ke order untuk referensi
                $order->payment_token = $snapToken;
                $order->save();

                // f. Hapus keranjang belanja
                Cart::where('user_id', $user->id)->delete();

                DB::commit();

                // g. Kembalikan view dengan Snap Token
                $addresses = Address::where('user_id', $user->id)->latest()->get(); // Ambil lagi untuk dikirim ke view
                return view('checkout.index', compact('cartItems', 'addresses', 'snapToken'));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

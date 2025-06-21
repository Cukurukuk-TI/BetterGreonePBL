<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {

        Log::info('Midtrans notification received:', $request->all());

    try {
        // Set konfigurasi Midtrans
        Config::$isProduction = config('midtrans.is_production');
        Config::$serverKey = config('midtrans.server_key');

        // Terima notifikasi dalam format JSON
        $notification = new \Midtrans\Notification();

        // Ambil data dari notifikasi
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $orderIdMidtrans = $notification->order_id;
        $grossAmount = $notification->gross_amount;

        // Ekstrak ID order dari string 'ORDER-123-TIMESTAMP'
        $orderId = explode('-', $orderIdMidtrans)[1];

        // Cari order di database
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Verifikasi signature key untuk keamanan
        $signatureKey = hash('sha512', $orderIdMidtrans . $notification->status_code . $grossAmount . config('midtrans.server_key'));
        if ($signatureKey != $notification->signature_key) {
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        // Lakukan update HANYA JIKA status order masih 'pending'
        if ($order->status === 'pending') {
            DB::transaction(function () use ($order, $transactionStatus) {
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    // Pembayaran berhasil
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing', // Ubah status order menjadi 'diproses'
                    ]);

                    // Di sinilah tempat yang TEPAT untuk mengurangi stok produk
                    foreach ($order->orderItems as $item) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                } elseif ($transactionStatus == 'pending') {
                    // Pembayaran masih tertunda
                    $order->update(['payment_status' => 'pending']);
                } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    // Pembayaran gagal atau dibatalkan
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled', // Batalkan pesanan
                    ]);
                }
            });
        }

        // Beri response OK ke Midtrans
        return response()->json(['message' => 'Notification handled successfully.']);

        } catch (\Exception $e) {
            // 3. Tangkap dan log error apapun yang terjadi
            Log::error('Webhook Error: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json(['message' => 'An error occurred.'], 500);
        }

    }
}

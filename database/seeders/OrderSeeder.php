<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil 1 user pelanggan dan beberapa produk secara acak
        $user = User::where('is_admin', false)->first();
        $products = Product::inRandomOrder()->limit(5)->get();

        // [FIX 1] Pengecekan awal yang lebih ketat, kita butuh minimal 3 produk untuk 2 order
        if (!$user || $products->count() < 3) {
            $this->command->info('Tidak ada cukup data user atau produk untuk membuat pesanan dummy. Pastikan ada minimal 1 user pelanggan dan 3 produk.');
            return;
        }

        // --- Buat Pesanan Pertama ---
        $order1 = Order::create([
            'user_id' => $user->id,
            'grand_total' => 0,
            'shipping_address' => 'Jalan Sudirman No. 123, Padang',
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $total1 = 0;

        // [FIX 2] Tambahkan pengecekan untuk setiap produk
        $product1 = $products->get(0);
        if ($product1) {
            $order1->orderItems()->create([
                'product_id' => $product1->id,
                'quantity' => 2,
                'price' => $product1->price
            ]);
            $total1 += $product1->price * 2;
        }

        $product2 = $products->get(1);
        if ($product2) {
            $order1->orderItems()->create([
                'product_id' => $product2->id,
                'quantity' => 1,
                'price' => $product2->price
            ]);
            $total1 += $product2->price * 1;
        }

        $order1->update(['grand_total' => $total1]);
        $this->command->info('Pesanan dummy pertama berhasil dibuat.');

        // --- Buat Pesanan Kedua ---
        $order2 = Order::create([
            'user_id' => $user->id,
            'grand_total' => 0,
            'shipping_address' => 'Jalan Khatib Sulaiman No. 45, Padang',
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);

        $total2 = 0;

        $product3 = $products->get(2);
        if ($product3) {
            $order2->orderItems()->create([
                'product_id' => $product3->id,
                'quantity' => 3,
                'price' => $product3->price
            ]);
            $total2 += $product3->price * 3;
        }

        $order2->update(['grand_total' => $total2]);
        $this->command->info('Pesanan dummy kedua berhasil dibuat.');
    }
}

<x-profile-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Detail Pesanan
                    </h2>
                    <p class="text-gray-500 mt-1">ID Pesanan: #{{ $order->id }}</p>
                    <p class="text-gray-500">Tanggal: {{ $order->created_at->format('d F Y') }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @if($order->status == 'pending') bg-yellow-200 text-yellow-800 @endif
                    @if($order->status == 'processing') bg-blue-200 text-blue-800 @endif
                    @if($order->status == 'shipped') bg-green-200 text-green-800 @endif
                    @if($order->status == 'completed') bg-gray-200 text-gray-800 @endif
                    @if($order->status == 'cancelled') bg-red-200 text-red-800 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Info Pengiriman --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alamat Pengiriman</h3>
                    <div class="mt-2 text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($order->shipping_address)) !!}
                    </div>
                </div>
                {{-- Ringkasan Pembayaran --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Pembayaran</h3>
                    <div class="mt-2 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status Pembayaran:</span>
                            <span class="font-medium @if($order->payment_status == 'paid') text-green-500 @else text-yellow-500 @endif">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim:</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total Pesanan:</span>
                            <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Item Pesanan --}}
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Item yang Dipesan</h3>
                <div class="space-y-4">
                    @foreach ($order->orderItems as $item)
                        <div class="flex items-center p-4 border rounded-lg dark:border-gray-700">
                            <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 mr-4 rounded object-cover">
                            <div class="flex-grow">
                                <p class="font-semibold">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right font-semibold">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('profile.orders') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600">
                    Kembali ke Riwayat Pesanan
                </a>
            </div>

        </div>
    </div>
</x-profile-layout>

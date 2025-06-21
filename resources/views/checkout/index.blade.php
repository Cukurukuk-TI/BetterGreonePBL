<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form utama untuk membuat pesanan --}}
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    {{-- Kolom Kiri: Alamat & Pengiriman --}}
                    <div class="md:col-span-2">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Pilih Alamat Pengiriman
                            </h3>

                            <div class="space-y-4">
                                @forelse ($addresses as $address)
                                    <label for="address-{{ $address->id }}" class="flex items-center p-4 border dark:border-gray-700 rounded-lg cursor-pointer">
                                        <input type="radio" name="address_id" id="address-{{ $address->id }}" value="{{ $address->id }}" class="h-4 w-4 text-green-600 focus:ring-green-500" @if($address->is_primary) checked @endif>
                                        <div class="ms-4 text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $address->label }} @if($address->is_primary) (Utama) @endif</p>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $address->recipient_name }} ({{ $address->phone_number }})</p>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $address->full_address }}</p>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-gray-500">Anda belum memiliki alamat tersimpan. <a href="#" class="text-green-600 hover:underline">Tambah Alamat Baru</a></p>
                                @endforelse
                            </div>
                            @error('address_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>

                    {{-- Kolom Kanan: Ringkasan Pesanan & Tombol Bayar --}}
                    <div class="md:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Ringkasan Pesanan
                            </h3>

                            @php
                                $subtotal = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
                                // Untuk sementara, ongkir kita buat statis
                                $shippingCost = 15000;
                                $grandTotal = $subtotal + $shippingCost;
                            @endphp

                            @foreach ($cartItems as $item)
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $item->product->name }} <span class="text-xs">x{{ $item->quantity }}</span></span>
                                    <span class="font-semibold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach

                            <hr class="my-4 dark:border-gray-700">

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>

                            <hr class="my-4 dark:border-gray-700">

                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-6">
                                <x-primary-button type="submit" class="w-full text-center justify-center">
                                    {{ __('Buat Pesanan') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

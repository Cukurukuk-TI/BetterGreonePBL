<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($cartItems->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Produk</th>
                                        <th scope="col" class="px-6 py-3">Harga</th>
                                        <th scope="col" class="px-6 py-3">Kuantitas</th>
                                        <th scope="col" class="px-6 py-3">Subtotal</th>
                                        <th scope="col" class="px-6 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center">
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 mr-4 rounded">
                                                {{ $item->product->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{-- Tombol Hapus akan diimplementasikan di commit selanjutnya --}}
                                                <button class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 text-right">
                            <h3 class="text-xl font-bold">
                                Total: Rp {{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 0, ',', '.') }}
                            </h3>
                            <x-primary-button class="mt-4">
                                {{ __('Lanjut ke Checkout') }}
                            </x-primary-button>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <h3 class="text-xl font-semibold">Keranjang belanja Anda masih kosong.</h3>
                            <a href="{{ route('produk.index') }}" class="mt-4 inline-block text-green-600 hover:underline">
                                Mulai Belanja Sekarang
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

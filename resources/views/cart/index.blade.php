<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-gray-900 dark:text-green-400" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- [PENTING] Tambahkan juga notifikasi untuk 'error' dari controller --}}
                    @if(session('error'))
                        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-900 dark:text-red-400" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif


                    @if ($cartItems->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Produk</th>
                                        <th scope="col" class="px-6 py-3">Harga</th>
                                        <th scope="col" class="px-6 py-3 text-center">Kuantitas</th>
                                        <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 align-middle">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center">
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 mr-4 rounded">
                                                <span>{{ $item->product->name }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-center space-x-3">
                                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                                        <button type="submit" class="p-1 border rounded-full" @if($item->quantity <= 1) disabled @endif>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                        </button>
                                                    </form>

                                                    <span class="font-semibold">{{ $item->quantity }}</span>

                                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                        <button type="submit" class="p-1 border rounded-full">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>

                                                @if ($item->quantity > $item->product->stock)
                                                    <p class="text-xs text-red-500 mt-1 text-center">Stok tidak cukup! (Sisa: {{ $item->product->stock }})</p>
                                                @endif

                                            </td>
                                            <td class="px-6 py-4 text-right font-semibold">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 text-right">
                            <h3 class="text-xl font-bold">
                                Total: <span>Rp {{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 0, ',', '.') }}</span>
                            </h3>
                            <a href="{{ route('checkout.index') }}">
                                <x-primary-button class="mt-4 bg-green-600 hover:bg-green-700">
                                    {{ __('Lanjut ke Checkout') }}
                                </x-primary-button>
                            </a>
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

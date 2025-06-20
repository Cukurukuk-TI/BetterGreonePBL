<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($products->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($products as $product)
                                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                                    <a href="#"> {{-- Arahkan ke detail produk nanti --}}
                                        <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="p-4 flex flex-col flex-grow">
                                        <p class="text-xs font-semibold text-gray-500 uppercase">{{ $product->category->name }}</p>
                                        <h3 class="text-lg font-semibold mt-1">{{ $product->name }}</h3>
                                        <p class="text-lg font-bold text-emerald-500 mt-2">
                                            {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        <div class="mt-4 flex-grow"></div> {{-- Pendorong agar tombol ke bawah --}}
                                        <a href="#" class="w-full text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }} {{-- Untuk navigasi halaman (pagination) --}}
                        </div>
                    @else
                        <p>Belum ada produk yang tersedia saat ini.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

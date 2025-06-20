<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg shadow-md">
                        </div>


                        <div class="flex flex-col">
                            <p class="text-sm font-semibold text-gray-500 uppercase">{{ $product->category->name }}</p>
                            <h1 class="text-4xl font-bold mt-2">{{ $product->name }}</h1>

                            <p class="text-3xl font-bold text-emerald-500 mt-4">
                                {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                            </p>

                            <div class="mt-4 text-gray-600 dark:text-gray-300">
                                <p>Stok Tersedia: <span class="font-semibold">{{ $product->stock }}</span></p>
                            </div>

                            <div class="mt-6 prose dark:prose-invert max-w-none">
                                {!! $product->description !!}
                            </div>

                            <div class="mt-8 flex-grow"></div> {{-- Pendorong agar tombol ke bawah --}}

                            <div class="mt-4">
                                <button class="w-full text-center bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300 text-lg font-bold">
                                    <span class="inline-flex items-center">
                                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c.51 0 .962-.344 1.087-.849l1.85-6.974A1.125 1.125 0 0 0 16.5 3.75H5.88a1.125 1.125 0 0 0-1.124 1.314l2.258 8.467c.13.49.577.848 1.087.848H18.5a1.125 1.125 0 0 0 1.125-1.125l-8.46-1.409a1.125 1.125 0 0 1-.944-1.359Z" /></svg>
                                        Tambah ke Keranjang
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

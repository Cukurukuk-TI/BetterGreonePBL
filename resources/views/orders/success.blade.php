<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesanan Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-center text-gray-900 dark:text-gray-100">

                    {{-- Ikon Centang --}}
                    <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

                    <h3 class="mt-4 text-2xl font-bold">Terima Kasih, {{ $order->user->name }}!</h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">Pesanan Anda telah kami terima dan sedang diproses.</p>

                    <div class="mt-6 text-left border-t border-b dark:border-gray-700 py-4">
                        <h4 class="text-lg font-semibold mb-2">Detail Pesanan</h4>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">ID Pesanan:</span>
                            <span class="font-mono">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-gray-600 dark:text-gray-400">Tanggal:</span>
                            <span>{{ $order->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-gray-600 dark:text-gray-400">Total Pembayaran:</span>
                            <span class="font-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Kembali ke Dashboard</a>
                        <span class="mx-2 text-gray-400">|</span>
                        <a href="{{ route('produk.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Lanjut Belanja</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

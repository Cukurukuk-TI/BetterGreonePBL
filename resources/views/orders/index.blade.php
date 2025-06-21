<x-profile-layout>
    {{-- Judul halaman sekarang dipindahkan ke dalam kartu --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

            {{-- [FIX] Judul sekarang ada di sini --}}
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Riwayat Pesanan Saya
            </h2>

            <div class="mt-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID Pesanan</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Total</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->created_at->format('d F Y') }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($order->status == 'pending') bg-yellow-200 text-yellow-800 @endif
                                            @if($order->status == 'processing') bg-blue-200 text-blue-800 @endif
                                            @if($order->status == 'shipped') bg-green-200 text-green-800 @endif
                                            @if($order->status == 'completed') bg-gray-200 text-gray-800 @endif
                                            @if($order->status == 'cancelled') bg-red-200 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('profile.orders.show', $order) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10">
                                        <p>Anda belum memiliki riwayat pesanan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link untuk Paginasi --}}
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>

        </div>
    </div>
</x-profile-layout>

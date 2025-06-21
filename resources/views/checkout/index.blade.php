<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    {{-- [KOLOM KIRI] Berisi 4 Kartu Proses Checkout --}}
                    <div class="lg:col-span-2 space-y-6">

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Detail Pesanan
                                </h3>
                                <div class="space-y-4">
                                    @foreach ($cartItems as $item)
                                        <div class="flex items-start space-x-4">
                                            <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded object-cover">
                                            <div class="flex-grow">
                                                <p class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-right font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" id="address-card">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Alamat Pengiriman
                                </h3>
                                <div class="space-y-4">
                                    @forelse ($addresses as $address)
                                        <label for="address-{{ $address->id }}" class="flex items-start p-4 border rounded-lg cursor-pointer has-[:checked]:border-green-600 has-[:checked]:ring-2 has-[:checked]:ring-green-200 dark:border-gray-700">
                                            <input type="radio" name="address_id" id="address-{{ $address->id }}" value="{{ $address->id }}" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" {{ $address->is_default ? 'checked' : '' }}>
                                            <div class="ms-4 text-sm">
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $address->label }}
                                                    @if($address->is_primary)<span class="text-xs font-semibold text-green-600 bg-green-100 dark:bg-green-900 dark:text-green-200 px-2 py-0.5 rounded-full">Utama</span>@endif
                                                </p>
                                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $address->recipient_name }} ({{ $address->phone_number }})</p>
                                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $address->full_address }}</p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-gray-500">Anda belum memiliki alamat tersimpan. <a href="#" class="text-green-600 hover:underline">Tambah Alamat Baru</a></p>
                                    @endforelse
                                </div>
                                @error('address_id')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                             <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Metode Pengiriman
                                </h3>
                                {{-- [FIX 2] Mengubah layout menjadi 2 kolom --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label for="shipping-pickup" class="flex items-center p-4 border rounded-lg cursor-pointer has-[:checked]:border-green-600 has-[:checked]:ring-2 has-[:checked]:ring-green-200 dark:border-gray-700">
                                        <input type="radio" name="shipping_method" id="shipping-pickup" value="pickup" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" checked>
                                        <div class="ms-4 text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Ambil di Tempat</p>
                                            <p class="text-gray-600 dark:text-gray-400">Gratis</p>
                                        </div>
                                    </label>
                                    <label for="shipping-delivery" class="flex items-center p-4 border rounded-lg cursor-pointer has-[:checked]:border-green-600 has-[:checked]:ring-2 has-[:checked]:ring-green-200 dark:border-gray-700">
                                        <input type="radio" name="shipping_method" id="shipping-delivery" value="delivery" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" >
                                        <div class="ms-4 text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Diantar Kurir Toko</p>
                                            <p class="text-gray-600 dark:text-gray-400">Biaya Rp 10.000</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Metode Pembayaran
                                </h3>
                                {{-- [FIX 2] Mengubah layout menjadi 2 kolom --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label for="payment-cod" class="flex items-center p-4 border rounded-lg cursor-pointer has-[:checked]:border-green-600 has-[:checked]:ring-2 has-[:checked]:ring-green-200 dark:border-gray-700">
                                        <input type="radio" name="payment_method" id="payment-cod" value="cod" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" checked>
                                        <div class="ms-4 text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">COD (Bayar di Tempat)</p>
                                        </div>
                                    </label>
                                    <label for="payment-transfer" class="flex items-center p-4 border rounded-lg cursor-pointer has-[:checked]:border-green-600 has-[:checked]:ring-2 has-[:checked]:ring-green-200 dark:border-gray-700">
                                        <input type="radio" name="payment_method" id="payment-transfer" value="transfer" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <div class="ms-4 text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Pembayaran Online (Transfer)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- [KOLOM KANAN] Ringkasan Belanja --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-28">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Ringkasan Belanja
                            </h3>

                            @php
                                $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
                            @endphp

                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                    <span id="shipping-cost-text">Rp 10.000</span>
                                </div>
                            </div>

                            <hr class="my-4 dark:border-gray-700">

                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Pembayaran</span>
                                <span id="grand-total-text">Rp {{ number_format($subtotal + 10000, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-6">
                                <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="10000">
                                <input type="hidden" name="grand_total" id="grand-total-input" value="{{ $subtotal + 10000 }}">

                                <x-primary-button type="button" id="process-payment-button" class="w-full text-center justify-center">
                                    {{ __('Buat Pesanan (COD)') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const shippingMethodRadios = document.querySelectorAll('input[name="shipping_method"]');
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const addressCard = document.getElementById('address-card');
    const shippingCostText = document.getElementById('shipping-cost-text');
    const grandTotalText = document.getElementById('grand-total-text');
    const processButton = document.getElementById('process-payment-button');
    const processButtonText = processButton.querySelector('span') || processButton;

    const subtotal = {{ $subtotal }};
    let shippingCost = 10000;

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

    function updateTotals() {
        shippingCostText.textContent = formatRupiah(shippingCost);
        const grandTotal = subtotal + shippingCost;
        grandTotalText.textContent = formatRupiah(grandTotal);
        document.getElementById('shipping-cost-input').value = shippingCost;
        document.getElementById('grand-total-input').value = grandTotal;
    }

    shippingMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'delivery') {
                addressCard.style.display = 'block';
                shippingCost = 10000;
            } else {
                addressCard.style.display = 'none';
                shippingCost = 0;
            }
            updateTotals();
        });
    });

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'cod') {
                processButtonText.textContent = 'Buat Pesanan (COD)';
            } else {
                processButtonText.textContent = 'Lanjutkan ke Pembayaran';
            }
        });
    });

    processButton.addEventListener('click', function() {
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const form = document.getElementById('checkout-form');

        if (selectedPaymentMethod === 'cod') {
            form.submit();
        } else {
            alert('Fungsi pembayaran online (Midtrans) akan dihubungkan di langkah selanjutnya.');
        }
    });
});
</script>
@endpush
</x-app-layout>

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header dengan Filter dan Stats -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">Katalog Produk</h1>
                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $products->total() }} produk
                </span>
            </div>

            <div class="flex items-center gap-3">
                <!-- Filter Button -->
                <button
                    wire:click="openFilterModal"
                    class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 shadow-sm {{ $this->hasActiveFilters() ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : '' }}"
                >
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Filter</span>
                    @if($this->hasActiveFilters())
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    @endif
                </button>

                <!-- Reset Filter Button (hanya muncul jika ada filter aktif) -->
                @if($this->hasActiveFilters())
                    <button
                        wire:click="resetFilters"
                        class="flex items-center gap-2 px-4 py-2 bg-red dark:bg-red border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="font-medium">Reset</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Active Filters Display -->
        @if($this->hasActiveFilters())
            <div class="mb-6 flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Filter aktif:</span>

                @if($sort !== 'created_at-desc')
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full text-sm">
                        Urutan: {{
                            collect([
                                'created_at-desc' => 'Terbaru',
                                'created_at-asc' => 'Terlama',
                                'price-asc' => 'Harga Terendah',
                                'price-desc' => 'Harga Tertinggi',
                                'name-asc' => 'Nama A-Z',
                                'name-desc' => 'Nama Z-A'
                            ])->get($sort, 'Terbaru')
                        }}
                    </span>
                @endif

                @if($minPrice)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm">
                        Min: Rp {{ number_format($minPrice, 0, ',', '.') }}
                    </span>
                @endif

                @if($maxPrice)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm">
                        Max: Rp {{ number_format($maxPrice, 0, ',', '.') }}
                    </span>
                @endif
            </div>
        @endif

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                    <a href="{{ route('produk.show', $product->slug) }}" class="block">
                        <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                src="{{ asset('storage/' . $product->image_path) }}"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            >
                        </div>
                    </a>

                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full uppercase tracking-wider">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 line-clamp-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                            {{ $product->name }}
                        </h3>

                        <div class="flex items-center justify-between">
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <a href="{{ route('produk.show', $product->slug) }}"
                           class="mt-4 w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 group">
                            <span>Lihat Detail</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V9a2 2 0 01-2 2H9a2 2 0 01-2-2V5a2 2 0 012-2h8a2 2 0 012 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Tidak ada produk ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center">Coba ubah filter atau kriteria pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Filter Modal -->
    @if($showFilterModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showFilterModal') }" x-show="show">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="closeFilterModal"
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                            </svg>
                            Filter & Urutkan
                        </h3>
                        <button
                            wire:click="closeFilterModal"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="space-y-6">
                        <!-- Sorting -->
                        <div>
                            <label for="tempSort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Urutkan Berdasarkan
                            </label>
                            <select
                                id="tempSort"
                                wire:model="tempSort"
                                class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-dark-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-3"
                            >
                                <option value="created_at-desc">Terbaru</option>
                                <option value="created_at-asc">Terlama</option>
                                <option value="price-asc">Harga Terendah</option>
                                <option value="price-desc">Harga Tertinggi</option>
                                <option value="name-asc">Nama A-Z</option>
                                <option value="name-desc">Nama Z-A</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kisaran Harga
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <input
                                        type="number"
                                        wire:model="tempMinPrice"
                                        placeholder="Harga minimum"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-3"
                                    >
                                </div>
                                <div>
                                    <input
                                        type="number"
                                        wire:model="tempMaxPrice"
                                        placeholder="Harga maksimum"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-3"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <button
                            wire:click="resetFilters"
                            class="flex-1 px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 shadow-sm"
                        >
                            Reset Filter
                        </button>
                        <button
                            wire:click="applyFilters"
                            class="flex-1 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

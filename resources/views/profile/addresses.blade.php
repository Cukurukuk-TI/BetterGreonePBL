{{-- resources/views/profile/addresses.blade.php --}}
<x-profile-layout>
    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-full">
                <header class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Daftar Alamat Pengiriman
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Kelola alamat pengiriman Anda di sini.
                        </p>
                    </div>
                    <a href="{{ route('profile.addresses.create') }}">
                        <x-primary-button>Tambah Alamat</x-primary-button>
                    </a>
                </header>

                <div class="mt-6 space-y-4">
                    @forelse ($addresses as $address)
                        <div class="p-4 border dark:border-gray-700 rounded-lg flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-3">
                                    {{-- Label Alamat (Rumah/Kantor) --}}
                                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $address->label }}</h3>

                                    {{-- Badge Alamat Utama --}}
                                    @if ($address->is_default)
                                        <span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Utama</span>
                                    @endif
                                </div>

                                {{-- Detail Alamat --}}
                                <p class="mt-1 text-sm font-bold text-gray-700 dark:text-gray-300">{{ $address->recipient_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $address->phone_number }}</p>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $address->full_address }}, {{ $address->city }}, {{ $address->province }}, {{ $address->postal_code }}
                                </p>
                            </div>
                            <div>
                                {{-- Tombol Aksi (Ubah/Hapus) disiapkan untuk commit selanjutnya --}}
                                <x-secondary-button disabled>Ubah</x-secondary-button>
                            </div>
                        </div>
                    @empty
                        {{-- Pesan jika tidak ada alamat sama sekali --}}
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed dark:border-gray-700 rounded-lg">
                            <p>Anda belum menambahkan alamat pengiriman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-profile-layout>

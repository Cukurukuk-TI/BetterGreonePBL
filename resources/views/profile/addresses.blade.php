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

                @if (session('status') === 'address-added')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400 mt-4"
                    >{{ __('Alamat baru berhasil ditambahkan.') }}</p>
                @elseif (session('status') === 'address-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400 mt-4"
                    >{{ __('Alamat berhasil diperbarui.') }}</p>
                    @elseif (session('status') === 'address-deleted')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400 mt-4"
                    >{{ __('Alamat berhasil dihapus.') }}</p>
                    @endif

                    {{-- Pesan Error --}}
                    @if (session('error') === 'cannot-delete-default')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="text-sm text-red-600 dark:text-red-400 mt-4">{{ __('Anda tidak dapat menghapus alamat utama. Silakan ganti alamat utama terlebih dahulu.') }}</p>
                    @endif

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
                            <div class="flex flex-col items-start gap-2">
                                {{-- Tombol Ubah --}}
                                <a href="{{ route('profile.addresses.edit', $address) }}" class="w-full">
                                    <x-secondary-button class="w-full justify-center">
                                        {{ __('Ubah') }}
                                    </x-secondary-button>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form method="post" action="{{ route('profile.addresses.destroy', $address) }}" class="w-full">
                                    @csrf
                                    @method('delete')

                                    <x-danger-button
                                        class="w-full justify-center"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')"
                                    >
                                        {{ __('Hapus') }}
                                    </x-danger-button>
                                </form>
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

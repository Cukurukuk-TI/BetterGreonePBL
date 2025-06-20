{{-- resources/views/components/profile-layout.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col md:flex-row gap-6">

                <div class="w-full md:w-1/4">
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                        <nav class="space-y-1">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm font-medium rounded-md
                               {{ request()->routeIs('profile.edit') ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Informasi Pribadi
                            </a>

                            <a href="{{ route('profile.account') }}"
                               class="block px-4 py-2 text-sm font-medium rounded-md
                               {{ request()->routeIs('profile.account') ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Informasi Akun
                            </a>

                            <span class="block px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 cursor-not-allowed">
                                Pesanan
                            </span>

                            <a href="{{ route('profile.addresses') }}"
                               class="block px-4 py-2 text-sm font-medium rounded-md
                               {{ request()->routeIs('profile.addreses') ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Alamat
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Keluar
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                <div class="w-full md:w-3/4">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

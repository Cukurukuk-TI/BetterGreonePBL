{{-- resources/views/profile/addresses-edit.blade.php --}}
<x-profile-layout>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Ubah Alamat
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Perbarui detail alamat pengiriman Anda.
                </p>
            </header>

            <form method="post" action="{{ route('profile.addresses.update', $address) }}" class="mt-6 space-y-6">
                @csrf
                @method('patch') {{-- Method 'PATCH' untuk proses update --}}

                <div>
                    <x-input-label for="label" :value="__('Label Alamat (Contoh: Rumah, Kantor)')" />
                    <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" :value="old('label', $address->label)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('label')" />
                </div>

                <div>
                    <x-input-label for="recipient_name" :value="__('Nama Penerima')" />
                    <x-text-input id="recipient_name" name="recipient_name" type="text" class="mt-1 block w-full" :value="old('recipient_name', $address->recipient_name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('recipient_name')" />
                </div>

                <div>
                    <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                    <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $address->phone_number)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                </div>

                <div>
                    <x-input-label for="full_address" :value="__('Alamat Lengkap')" />
                    <textarea id="full_address" name="full_address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('full_address', $address->full_address) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('full_address')" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="city" :value="__('Kota/Kabupaten')" />
                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $address->city)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('city')" />
                    </div>
                    <div>
                        <x-input-label for="province" :value="__('Provinsi')" />
                        <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" :value="old('province', $address->province)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('province')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="postal_code" :value="__('Kode Pos')" />
                    <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', $address->postal_code)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                </div>

                <div>
                    <x-input-label for="map" :value="__('Ubah Lokasi di Peta')" />
                    <div id="map" class="mt-1 h-64 w-full rounded-md shadow-sm"></div>
                </div>

                {{-- Input tersembunyi untuk menyimpan koordinat --}}
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $address->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $address->longitude) }}">

                <div class="block mt-4">
                    <label for="is_default" class="inline-flex items-center">
                        <input id="is_default" type="checkbox" name="is_default" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" @if(old('is_default', $address->is_default)) checked @endif>
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Jadikan Alamat Utama') }}</span>
                    </label>
                </div>


                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                    <a href="{{ route('profile.addresses') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-profile-layout>


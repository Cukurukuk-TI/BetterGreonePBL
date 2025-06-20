{{-- resources/views/profile/edit.blade.php --}}
<x-profile-layout>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>
</x-profile-layout>

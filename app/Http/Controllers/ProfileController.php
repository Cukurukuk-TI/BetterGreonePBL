<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Address;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Logika untuk upload foto profil
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $request->user()->profile_photo_path = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function addresses()
    {
        // Ambil alamat dari user yang sedang login menggunakan relasi yg dibuat di Commit 2
        $addresses = Auth::user()->addresses()->latest()->get();

        // Tampilkan view dan kirim data alamat ke dalamnya
        return view('profile.addresses', [
            'addresses' => $addresses,
        ]);
    }

        public function createAddress()
    {
        return view('profile.addresses-create');
    }

    /**
     * TAMBAHKAN METHOD BARU DI BAWAH INI
     * Menyimpan alamat baru ke database.
     */
    public function storeAddress(Request $request)
    {
        // 1. Validasi semua input dari form
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'full_address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'is_default' => ['sometimes', 'boolean'],
            'latitude' => ['nullable', 'string', 'max:255'],
            'longitude' => ['nullable', 'string', 'max:255'],

        ]);

        if ($request->has('is_default')) {
            $validated['is_default'] = true;
            Auth::user()->addresses()->update(['is_default' => false]);
        } else {
            $validated['is_default'] = false;
        }

        Auth::user()->addresses()->create($validated);

        return redirect()->route('profile.addresses')->with('status', 'address-added');
    }

        public function editAddress(Address $address)
    {
        // Keamanan: Pastikan user hanya bisa mengedit alamat miliknya sendiri.
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('profile.addresses-edit', [
            'address' => $address,
        ]);
    }

    public function updateAddress(Request $request, Address $address)
    {
        // Keamanan: Pastikan user hanya bisa mengupdate alamat miliknya sendiri.
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'full_address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'is_default' => ['sometimes', 'boolean'],
            'latitude' => ['nullable', 'string', 'max:255'],
            'longitude' => ['nullable', 'string', 'max:255'],

        ]);

        if ($request->has('is_default')) {
            $validated['is_default'] = true;
            // Jadikan semua alamat lain milik user ini sebagai 'not default'
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        } else {
            $validated['is_default'] = false;
        }

        $address->update($validated);

        return redirect()->route('profile.addresses')->with('status', 'address-updated');
    }

        public function destroyAddress(Address $address)
    {
        // Keamanan: Pastikan user hanya bisa menghapus alamat miliknya sendiri.
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Aturan tambahan: Jangan biarkan user menghapus alamat utamanya.
        if ($address->is_default) {
            return redirect()->route('profile.addresses')->with('error', 'cannot-delete-default');
        }

        $address->delete();

        return redirect()->route('profile.addresses')->with('status', 'address-deleted');
    }

}

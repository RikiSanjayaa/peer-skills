<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a user's public profile.
     */
    public function show(User $user): View
    {
        $user->load('seller.gigs.category');

        // TODO: Add order count when orders are implemented
        $orderCount = 0; // Placeholder for now

        return view('profile.show', [
            'user' => $user,
            'orderCount' => $orderCount,
        ]);
    }

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
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.platform' => ['nullable', 'string', 'max:50'],
            'social_links.*.url' => ['nullable', 'url', 'max:255'],
        ], [
            'avatar.image' => 'Foto profil harus berupa gambar (JPG, PNG, GIF).',
            'avatar.mimes' => 'Format foto profil tidak didukung. Gunakan JPG, PNG, atau GIF.',
            'avatar.max' => 'Ukuran foto profil terlalu besar. Maksimal 2MB.',
            'banner.image' => 'Banner harus berupa gambar (JPG, PNG, GIF).',
            'banner.mimes' => 'Format banner tidak didukung. Gunakan JPG, PNG, atau GIF.',
            'banner.max' => 'Ukuran banner terlalu besar. Maksimal 2MB.',
        ]);

        $user = $request->user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($user->banner) {
                Storage::disk('public')->delete($user->banner);
            }
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        // Filter out empty social links
        if (isset($validated['social_links'])) {
            $validated['social_links'] = array_filter($validated['social_links'], function ($link) {
                return !empty($link['platform']) && !empty($link['url']);
            });
            $validated['social_links'] = array_values($validated['social_links']);
        }

        // Check if email changed
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the user's avatar.
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return back()->with('success', 'Avatar removed successfully!');
    }

    /**
     * Remove the user's banner.
     */
    public function removeBanner(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
            $user->banner = null;
            $user->save();
        }

        return back()->with('success', 'Banner removed successfully!');
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

        // Delete avatar and banner files
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

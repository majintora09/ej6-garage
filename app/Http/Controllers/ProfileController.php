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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function settings(Request $request): View
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill(collect($validated)->except(['avatar', 'banner'])->all());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $this->replaceProfileImage($request, $user, 'avatar', 'avatar_path');
        $this->replaceProfileImage($request, $user, 'banner', 'banner_path');

        $user->save();
        $user->ensureProfileSlug();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    private function replaceProfileImage(ProfileUpdateRequest $request, User $user, string $input, string $column): void
    {
        if (! $request->hasFile($input)) {
            return;
        }

        $directory = "profiles/{$user->id}/{$input}";
        $path = $request->file($input)->storePublicly($directory, 'public');

        if (! $path) {
            return;
        }

        if ($user->{$column}) {
            Storage::disk('public')->delete($user->{$column});
        }

        $user->forceFill([$column => $path]);
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
}

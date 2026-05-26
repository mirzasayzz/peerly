<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $disk = config('filesystems.default');

            try {
                $path = $request->file('avatar')->store('avatars', $disk);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Avatar upload failed: ' . $e->getMessage());
                $path = false;
            }

            if ($path) {
                // Delete old avatar if exists and not using external URL
                if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                    try {
                        \Illuminate\Support\Facades\Storage::disk($disk)->delete($user->avatar);
                    } catch (\Exception $e) {
                        // Ignore delete errors for old avatar
                    }
                }
                $validated['avatar'] = $path;
            } else {
                return Redirect::route('profile.edit')
                    ->withErrors(['avatar' => 'Failed to upload image. Please check your storage configuration and try again.'])
                    ->withInput();
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('username')) {
            $user->username_changed_at = now();
        }

        $user->save();

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

        // Check if there is already a pending deletion request
        $exists = \App\Models\AccountDeletionRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if (!$exists) {
            \App\Models\AccountDeletionRequest::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'reason' => $request->input('reason'),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Your account deletion request has been submitted to the administrator for review.');
    }
}

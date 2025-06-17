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
    }    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $oldEmail = $user->email;
        $oldPhone = $user->phone;
        
        $user->fill($request->validated());

        // If email changed, reset email verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        // If phone changed, reset phone verification
        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        $user->save();

        $message = 'Profile updated successfully.';
        
        // Add verification notices
        if ($oldEmail !== $user->email) {
            $message .= ' Please verify your new email address.';
        }
        
        if ($oldPhone !== $user->phone && $user->phone) {
            $message .= ' Please verify your new phone number.';
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('message', $message);
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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
     * Update the user's account information.
     */
    public function updateAccount(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $request = $request->validated();

        $user->fill([
            'username' => $request['name'],
            'email' => $request['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'account-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'display_name' => ['required', 'string', 'max:20'],
            'pronouns' => ['required', 'array'],
            'pronouns.*' => ['string', 'max:3', Rule::in(['all', 'm', 'f', 'd', 'o'])], // changes made here must be reflected in profile.php lang files
            'bio_de' => ['required', 'string', 'max:50'],
            'bio_en' => ['required', 'string', 'max:50'],

            // validate avatar file
            'avatar' => ['nullable', 'image', 'max:1024', 'mimes:jpeg,webp', 'dimensions:min_width=100,min_height=100,max_width=1024,max_height=1024,ratio=1:1'],
        ]);

        $wasChanged = false;

        if ($user->display_name !== $request->display_name) {
            $user->display_name = $request->display_name;
            $wasChanged = true;
        }

        if ($user->pronouns !== $request->pronouns) {
            // if all is selected, remove all other pronouns & only write all
            if (in_array('all', $request->pronouns)) {
                $user->pronouns = ['all'];
            } else {
                $user->pronouns = $request->pronouns;
            }
            $wasChanged = true;
        }

        if ($user->desc_de !== $request->bio_de) {
            $user->desc_de = $request->bio_de;
            $wasChanged = true;
        }

        if ($user->desc_en !== $request->bio_en) {
            $user->desc_en = $request->bio_en;
            $wasChanged = true;
        }

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $file = $request->file('avatar');
            $filename = $file->hashName();
            $path = 'img/users/';

            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($path . $user->avatar))) {
                unlink(public_path($path . $user->avatar));
            }

            // Store the new avatar
            $file->storeAs($path, $filename, 'public');
            $user->avatar = $filename;
            $wasChanged = true;
        }

        if ($wasChanged) {
            $user->save();
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        return Redirect::route('profile.edit')->with('status', 'no-changes');
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

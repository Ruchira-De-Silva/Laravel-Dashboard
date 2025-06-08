<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        // Auth::user() -> checks if the credentials saved in the session match the targeted user
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function updateUsername(Request $request)
    {
        /**
         * required - field must not be empty
         * string - must be string
         * max:255 - cannot exceed 255 characters
        */
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $user = User::find(Auth::id());

        if ($user->setUsername($request->username))
        {
            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully');
        }

        return redirect()->route('profile.edit')->with('error', 'Failed to update profile');
    }

    public function updatePassword(Request $request)
    {
        /**
         * required - field must not be empty
         * string - must be string
         * min:8 - must have at least 8 characters
         * confirmed - must match password_confirmation input field
        */
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::find(Auth::id());

        if ($user->updatePassword($request->password))
        {
            return redirect()->route('profile.edit')->with('success', 'Password updated successfully');
        }

        return redirect()->route('profile.edit')->with('error', 'Failed to update password');
    }
}
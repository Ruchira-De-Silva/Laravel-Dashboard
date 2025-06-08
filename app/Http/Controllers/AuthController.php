<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');  // Show the login form
    }

    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
                case 'manager':
                    return redirect()->route('manager.dashboard')->with('success', 'Welcome, Manager!');
                case 'staff':
                    return redirect()->route('staff.dashboard')->with('success', 'Welcome, Staff!');
                default:
                    return redirect('/')->with('error', 'Access Denied');
            }
        }
        // If login attempt fails
        return redirect()->route('login')->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}


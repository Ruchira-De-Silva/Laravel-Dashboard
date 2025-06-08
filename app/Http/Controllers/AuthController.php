<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        dd('At show login');
    //     if (Auth::check()) {
    //     return redirect()->route('dashboard');
    // }

        return view('livewire.pages.auth.login');  // Show the login form
    }

    public function login(Request $request)
    {
        // dd('At login');
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
                    // return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
                    echo("Admin Hello");
                case 'manager':
                    // return redirect()->route('manager.dashboard')->with('success', 'Welcome, Manager!');
                    echo("Manager Hello");
                case 'staff':
                    // return redirect()->route('staff.dashboard')->with('success', 'Welcome, Staff!');
                    echo("Staff Hello");
                default:
                    return redirect('/')->with('error', 'Invalid role');
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

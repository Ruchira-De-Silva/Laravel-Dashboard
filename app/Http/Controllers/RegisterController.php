<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        // Only allow access to the registration form for admin users
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'You are not authorized to access this page.');
        }

        return view('auth.register');
    }

    /**
     * Handle the registration process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate the input
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,staff',  // Ensure role is valid
            'date_of_birth' => 'required|date',
        ]);

        // dd("after validation");
        // Create the user
        $user = new User();
        $user->setFullName($request->full_name);
        $user->setUsername($request->username);
        $user->setEmail($request->email);
        $user->setPassword($request->password); // Hash the password
        $user->setRole($request->role);
        $user->setDOB($request->date_of_birth);
        $user->setDateHired();
        $user->setEmployeeCode();

        $user->save();
        // dd($user->__toString());

        session()->flash('success', 'User registered successfully.');
        return redirect()->route('registration');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/login')->with('error', 'You are not authorized to access this page.');
        }

        return redirect('/admin/' . Auth::user()->username)->with('error', 'Failed to redirect.');
    }
}

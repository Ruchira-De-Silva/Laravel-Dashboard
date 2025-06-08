<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        if (Auth::user()->role !== 'staff') {
            return redirect('/login')->with('error', 'You are not authorized to access this page.');
        }

        return redirect('/staff/' . Auth::user()->username)->with('error', 'Failed to redirect.');
    }
}

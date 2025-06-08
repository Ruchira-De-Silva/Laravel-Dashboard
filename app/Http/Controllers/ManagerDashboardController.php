<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    /**
     * Show the manager dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showDashboard()
    {
        if (Auth::user()->role !== 'manager') {
            return redirect('/login')->with('error', 'You are not authorized to access this page.');
        }

        return redirect('/manager/' . Auth::user()->username)->with('error', 'Failed to redirect.');
    }
}

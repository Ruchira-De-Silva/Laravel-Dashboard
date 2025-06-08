<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// Route::get('/manager/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
// Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');

Route::get('/login', [AuthController::class,
'showLoginForm'])->name('login');

Route::post('login', [AuthController::class,
'login'])->name('login.submit');

Route::post('logout', [AuthController::class,
'logout'])->name('logout');

Route::middleware(['auth'/*, 'role:admin'*/])
->get('/admin/register', [RegisterController::class,
'showRegisterForm']);

Route::post('register', [RegisterController::class,
'register'])->name('register.submit');

require __DIR__.'/auth.php';

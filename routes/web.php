<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Register your web routes here. These routes are loaded by
| RouteServiceProvider and are assigned the "web" middleware group.
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// --- Auth (Session-based) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- Authenticated routes ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// --- Admin routes (example) ---
// Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
// });

<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\CourseDetailController;
use App\Http\Controllers\Student\MyCoursesController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Course Catalog & Details
Route::get('/courses', [CourseCatalogController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseDetailController::class, 'show'])->name('courses.show');

// Language Switcher
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['vi', 'en'])) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');

// --- Password Reset Routes (Accessible for both guests and authenticated users) ---
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// --- Auth Routes (Guest Only) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // OAuth Social Login
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
});

// --- Auth Routes (Logged In) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // User Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // My Enrolled Courses
    Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('profile.my-courses');
});

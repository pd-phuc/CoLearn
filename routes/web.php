<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\CourseDetailController;
use App\Http\Controllers\Student\CartController;
use App\Http\Controllers\Student\CheckoutController;
use App\Http\Controllers\Student\LearningController;
use App\Http\Controllers\Student\MyCoursesController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Student\PaymentIpnController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\SePayWebhookController;
use App\Http\Controllers\Student\WalletController;
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

// Student Learning Player & Progress Tracking
Route::get('/learn/{course:slug}/{lesson?}', [LearningController::class, 'show'])->name('learn.show');
Route::post('/learn/lessons/{lesson}/toggle-complete', [LearningController::class, 'toggleComplete'])->name('learn.toggle-complete');

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

// --- Cart Routes (Accessible to all) ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{course}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{courseId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// --- Payment Webhooks & IPN (No Auth / CSRF Exempt) ---
Route::post('/payment/sepay/webhook', [SePayWebhookController::class, 'webhook'])->name('payment.sepay.webhook');

// --- Auth Routes (Logged In) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // User Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // My Enrolled Courses & CoLearn Wallet
    Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('profile.my-courses');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
    Route::get('/wallet/topup/{order}', [WalletController::class, 'showPendingTopup'])->name('wallet.topup.show');

    // Checkout & Payment Processing
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');

    // Real-Time VietQR Order Polling & Simulated Payment Helper
    Route::get('/orders/{order}/status', [PaymentIpnController::class, 'status'])->name('orders.status');
    Route::post('/orders/{order}/simulated-pay', [PaymentIpnController::class, 'simulatedPay'])->name('orders.simulated-pay');

    // Order History & Receipts
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

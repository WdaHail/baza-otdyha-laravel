<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services.index');
Route::get('/services/{service:slug}', [HomeController::class, 'service'])->name('services.show');
Route::get('/reviews', [HomeController::class, 'reviews'])->name('reviews.index');
Route::get('/contacts', [HomeController::class, 'contacts'])->name('contacts');
Route::post('/contacts', [HomeController::class, 'feedback'])->middleware('throttle:5,1')->name('contacts.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('booking.store');
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::post('/account/notifications/read', [AccountController::class, 'readNotifications'])->name('account.notifications.read');
    Route::post('/reviews', [ReviewController::class, 'store'])->middleware('throttle:3,1')->name('reviews.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::patch('/bookings/{booking}', [AdminController::class, 'bookingStatus'])->name('bookings.update');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
    Route::middleware('role:admin')->group(function () {
        Route::get('/services', [AdminController::class, 'services'])->name('services.index');
        Route::get('/services/create', [AdminController::class, 'serviceCreate'])->name('services.create');
        Route::post('/services', [AdminController::class, 'serviceStore'])->name('services.store');
        Route::get('/services/{service}/edit', [AdminController::class, 'serviceEdit'])->name('services.edit');
        Route::put('/services/{service}', [AdminController::class, 'serviceUpdate'])->name('services.update');
        Route::delete('/services/{service}', [AdminController::class, 'serviceDestroy'])->name('services.destroy');
        Route::get('/extras', [AdminController::class, 'extras'])->name('extras.index');
        Route::post('/extras', [AdminController::class, 'extraStore'])->name('extras.store');
        Route::put('/extras/{extra}', [AdminController::class, 'extraUpdate'])->name('extras.update');
        Route::delete('/extras/{extra}', [AdminController::class, 'extraDestroy'])->name('extras.destroy');
        Route::patch('/reviews/{review}', [AdminController::class, 'reviewStatus'])->name('reviews.update');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminController::class, 'userRole'])->name('users.role');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
        Route::put('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
        Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback.index');
        Route::patch('/feedback/{feedback}', [AdminController::class, 'feedbackUpdate'])->name('feedback.update');
    });
});

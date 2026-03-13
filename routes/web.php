<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\RegisterLinkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider under {locale} prefix.
| So "/" here means "/{locale}/"
|
*/

// Home route - main landing page (becomes /{locale}/)
Route::get('/', [Controllers\HomeController::class, 'index'])->name('home');

// Properties
Route::get('/properties', [Controllers\PropertyController::class, 'index'])->name('properties.index');

// Public Agencies Listing
Route::get('/agencies', [Controllers\AgenciesListingController::class, 'index'])->name('public.agencies.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/properties/create', [Controllers\PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [Controllers\PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [Controllers\PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [Controllers\PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [Controllers\PropertyController::class, 'destroy'])->name('properties.destroy');

    // Property Images
    Route::post('/properties/{property}/images', [Controllers\PropertyImageController::class, 'store'])->name('property-images.store');
    Route::put('/properties/{property}/images/{image}/main', [Controllers\PropertyImageController::class, 'makeMain'])->name('property-images.make-main');
    Route::delete('/properties/{property}/images/{image}', [Controllers\PropertyImageController::class, 'destroy'])->name('property-images.destroy');
});

Route::get('/properties/{slug}', [Controllers\PropertyController::class, 'show'])->name('properties.show');

Route::middleware(['guest'])->group(function () {
    Route::post('/register/start', [RegisterLinkController::class, 'start'])
        ->middleware(['throttle:login-otp-request'])
        ->name('register.start');

    Route::get('/register/complete', [RegisterLinkController::class, 'complete'])
        ->name('register.complete');

    Route::get('/login/otp', function () {
        return view('auth.login-otp');
    })->name('login.otp');

    Route::post('/login/otp', [OtpLoginController::class, 'request'])
        ->middleware(['throttle:login-otp-request'])
        ->name('login.otp.request');

    Route::post('/login/otp/verify', [OtpLoginController::class, 'verify'])
        ->middleware(['throttle:login-otp-verify'])
        ->name('login.otp.verify');
});

Route::resource('about-us', Controllers\AboutController::class);
Route::get('/privacy-policy', [Controllers\PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
Route::get('/terms-conditions', [Controllers\TermsConditionsController::class, 'index'])->name('terms-conditions.index');
Route::get('/check-login-status', [Controllers\HomeController::class, 'checkLoginStatus'])->name('check.login.status');


Route::get('/profile', function () {
    return view('auth.profile');
})->middleware(['auth', 'verified'])->name('profile.index');

// Authenticated user routes - Agency management
Route::middleware(['auth', 'verified'])->group(function () {
    // Agency management for users
    Route::prefix('agency')->name('agency.')->group(function () {
        Route::get('/', [Controllers\AgencyController::class, 'index'])->name('index');
        Route::get('form', [Controllers\AgencyController::class, 'show'])->name('show');
        Route::post('store', [Controllers\AgencyController::class, 'store'])->name('store');
        Route::put('update', [Controllers\AgencyController::class, 'update'])->name('update');
        Route::get('api/show', [Controllers\AgencyController::class, 'showApi'])->name('api.show');
    });

    // Subscription management for users
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [Controllers\SubscriptionsController::class, 'index'])->name('index');
        Route::post('upgrade', [Controllers\SubscriptionsController::class, 'upgrade'])->name('upgrade');
    });
});



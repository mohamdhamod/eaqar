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



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
| This file contains the backend routes
| Protected by middleware and user role admin
|
*/


Route::get('/',[Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');

Route::resource('roles',Controllers\Dashboard\RoleController::class);

// Users Management
Route::resource('users', Controllers\Dashboard\UsersController::class);
Route::put('users/change_password/{id}', [Controllers\Dashboard\UsersController::class, 'change_password'])
    ->name('users.change_password');

Route::resource('config_titles',Controllers\Dashboard\ConfigTitlesController::class);

Route::resource('config_images',Controllers\Dashboard\ConfigImagesController::class);
Route::resource('config_email_links',Controllers\Dashboard\ConfigEmailsLinksController::class);

Route::resource('configurations',Controllers\Dashboard\ConfigurationsController::class);
Route::post('configurations/{id?}/updateActiveStatus',[Controllers\Dashboard\ConfigurationsController::class,'updateActiveStatus'])->name('configurations.updateActiveStatus');

Route::resource('countries',Controllers\Dashboard\CountryController::class);
Route::post('countries/{id?}/updateActiveStatus',[Controllers\Dashboard\CountryController::class,'updateActiveStatus'])->name('countries.updateActiveStatus');



// Specialties Management
Route::resource('specialties', Controllers\Dashboard\SpecialtiesController::class);
Route::post('specialties/{id?}/updateActiveStatus', [Controllers\Dashboard\SpecialtiesController::class, 'updateActiveStatus'])->name('specialties.updateActiveStatus');

// Subscriptions Management
Route::resource('subscriptions', Controllers\Dashboard\SubscriptionsController::class);
Route::post('subscriptions/{id?}/updateActiveStatus', [Controllers\Dashboard\SubscriptionsController::class, 'updateActiveStatus'])->name('subscriptions.updateActiveStatus');

// Agencies Management
Route::resource('agencies', Controllers\Dashboard\AgenciesController::class)->only(['index']);
Route::post('agencies/{id}/toggle-active', [Controllers\Dashboard\AgenciesController::class, 'updateActiveStatus'])->name('agencies.updateActiveStatus');

// User Subscriptions Management
Route::resource('user-subscriptions', Controllers\Dashboard\UserSubscriptionsController::class)->only(['index']);
Route::post('user-subscriptions/{id}/toggle-active', [Controllers\Dashboard\UserSubscriptionsController::class, 'updateActiveStatus'])->name('user-subscriptions.updateActiveStatus');
Route::post('user-subscriptions/{id}/change-subscription', [Controllers\Dashboard\UserSubscriptionsController::class, 'changeSubscription'])->name('user-subscriptions.changeSubscription');

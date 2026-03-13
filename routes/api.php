<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Routing\Middleware\ThrottleRequests;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Properties (public, no auth required)
Route::get('/properties', [\App\Http\Controllers\Api\PropertyController::class, 'index'])
    ->name('api.properties.index');

// Agencies (public, no auth required)
Route::get('/agencies', [\App\Http\Controllers\Api\AgencyController::class, 'index'])
    ->name('api.agencies.index');

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);


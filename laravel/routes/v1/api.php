<?php

use App\Services\Auto\Http\Controllers\AutoController;
use App\Services\Auto\Http\Controllers\AutoMarkController;
use App\Services\Auto\Http\Controllers\AutoModelController;
use App\Services\User\Http\Controllers\v1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('auth/login', [AuthController::class, 'login'])->name('v1.auth.login');

$only = ['index', 'store', 'update', 'destroy'];
Route::resource('auto', AutoController::class, ['only' => $only]);
Route::resource('models', AutoModelController::class, ['only' => $only]);
Route::resource('marks', AutoMarkController::class, ['only' => $only]);

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [\App\Http\Controllers\IndexController::class, "index"])->name('home');
    Route::get('/dashboard', [\App\Http\Controllers\IndexController::class, "dashboard"])->name('dashboard');
    Route::get('/user', [\App\Http\Controllers\IndexController::class, "user"])->name('user');
    Route::get('/dev', [\App\Http\Controllers\IndexController::class, "showDev"])->name('dev');
    Route::get('/permission', [\App\Http\Controllers\IndexController::class, "permission"])->name('permission');
    Route::get('/register', [\App\Http\Controllers\IndexController::class, "register"])->name('register');
});

Route::get('/login', [\App\Http\Controllers\IndexController::class, "login"])->name('login');
Route::get('/logout', [\App\Http\Controllers\IndexController::class, "logout"])->name('logout');

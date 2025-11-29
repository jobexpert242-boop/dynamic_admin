<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'home'])->name('home');

// Route::get('/login', [LoginController::class, 'showLoginForm2'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::get('/register', [RegisterController::class, 'show'])->name('register');
// Route::post('/register', [RegisterController::class, 'store']);

// multi language route
Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'bn'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return back();
})->name('lang.switch');



// include admin route 
require __DIR__ . '/admin.php';
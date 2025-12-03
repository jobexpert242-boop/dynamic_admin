<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// include route
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';

Route::get('/', [UserController::class, 'home'])->name('home');


// External route link
// multi language route
Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'bn'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return back();
})->name('lang.switch');

// cache clear 
Route::post('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return back()->with('status', 'Cache cleared successfully!');
})->name('clear-cache');

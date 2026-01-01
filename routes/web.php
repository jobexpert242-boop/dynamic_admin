<?php

use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// all route here 
Route::get('/', [UserController::class, 'home'])->name('home');



Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});

// include route
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
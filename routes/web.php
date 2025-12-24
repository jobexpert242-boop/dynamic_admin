<?php

use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// all route here 
Route::get('/', [UserController::class, 'home'])->name('home');


// include route
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
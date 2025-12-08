<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Broadcast::routes(['middleware' => ['web', 'auth']]);

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'show'])->name('admin.register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'Adminlogin']);

    // logout 
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // forget password 
    Route::get('/forgetpassword', [ForgotPasswordController::class, 'create'])->name('admin.forgetpassword');
    Route::post('/forgetpassword', [ForgotPasswordController::class, 'store'])->middleware('throttle:3,60');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);
});

// middle ware route
Route::middleware(['auth', 'role:admin,sales,sup_admin,sub_admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // dashboard 
        Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

        // role and permission 
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        // menu 
        Route::get('/menus', [MenuController::class, 'index'])->name('menu.index');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

        // users 
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::match(['post', 'put'], 'users/{user?}', [UserController::class, 'store'])->name('admin.users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // profile 
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // only docs 
        Route::get('/docs', [SiteSettingController::class, 'docs'])->name('admin.docs');
        Route::post('/docs/update', [SiteSettingController::class, 'update'])->name('admin.docs.update');

        // Site Setting
        Route::get('/sitesettings', [SiteSettingController::class, 'siteSetting'])->name('admin.sitesetting');
        Route::post('/sitesetting/update', [SiteSettingController::class, 'siteSettingUpdate'])->name('admin.sitesetting.update');

        // billing 
        Route::get('/billing', [BillingController::class, 'billing'])->name('admin.billing');
        Route::get('/create-invoice', [BillingController::class, 'createInvoice'])->name('admin.createInvoice');
        Route::post('/store-invoice', [BillingController::class, 'storeInvoice'])->name('admin.invoice.store');
        Route::get('/edit-invoice/{invoice}', [BillingController::class, 'editInvoice'])->name('admin.invoice.edit');
        Route::put('/update-invoice/{invoice}', [BillingController::class, 'updateInvoice'])->name('admin.invoice.update');
        Route::delete('/invoice/{id}', [BillingController::class, 'deleteInvoice'])->name('admin.invoice.delete');
        Route::get('/invoice/{invoice}/view', [BillingController::class, 'viewInvoice'])->name('admin.invoice.view');
        Route::put('/invoice/update/status/{invoice}', [BillingController::class, 'invoiceUpdateStatus'])->name('invoice.update.status');
    });
});

// notifications 
Route::get('/admin/notifications', [AdminController::class, 'notifications'])->name('notifications.index');

// public billing 
Route::get('/invoice/{invoice}/{token}', [BillingController::class, 'publicInvoice'])->name('public.invoice');

// Route::get('/test-notify', function () {
//     auth()->user()->notify(new \App\Notifications\NewMessageNotification());
//     return 'Notification sent';
// })->middleware('auth');

// grapes js editor route 
Route::middleware(['auth', 'role:admin,sales,sup_admin,sub_admin'])->prefix('admin')->group(function () {
    Route::get('/grapesjs', [PageController::class, 'grapesjs'])->name('pages.grapesjs');
    Route::get('/editor', [PageController::class, 'home'])->name('pages.editor');
    Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::put('/pages/{id}', [PageController::class, 'update'])->name('pages.update');

    Route::post('/pages/{id}/grapes-store', [PageController::class, 'grapesStore'])->name('pages.grapes-store');
    Route::get('/pages/{id}/grapes-load', [PageController::class, 'grapesLoad'])
        ->name('pages.grapes-load');

    Route::post('/upload-image', [PageController::class, 'uploadImage'])->name('pages.uploadImage');
    Route::delete('/destroy/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
});

// External route link
// grapes js editor
Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');

// multi language route
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// cache clear 
Route::post('/clear-cache', [CacheController::class, 'clear'])->name('clear-cache');

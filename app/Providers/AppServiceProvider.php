<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Inertia::share('menus', function () {
        //     $user = auth()->user();

        //     if (!$user) return [];
        //     $menus = $user->menus()->where('status', 1)->orderBy('order_by')->get();
        //     $grouped = $menus->groupBy('parent_id');

        //     $buildTree = function ($parentId = null) use (&$buildTree, $grouped, $user) {
        //         return ($grouped[$parentId] ?? collect())->map(function ($menu) use ($buildTree, $user) {
        //             $permissions = is_array($menu->permission_class)
        //                 ? $menu->permission_class
        //                 : json_decode($menu->permission_class ?? '[]', true);

        //             $permissions = array_filter(array_map('trim', $permissions));

        //             if (!empty($permissions) && !$user->hasAnyPermission($permissions)) {
        //                 return null;
        //             }

        //             return [
        //                 'id' => $menu->id,
        //                 'title' => $menu->title,
        //                 'route' => $menu->route,
        //                 'icon' => $menu->icon,
        //                 'top' => $menu->top,
        //                 'left' => $menu->left,
        //                 'footer' => $menu->footer,
        //                 'children' => $buildTree($menu->id),
        //             ];
        //         })->filter()->values();
        //     };

        //     $tree = $buildTree();

        //     return [
        //         'top' => $tree->filter(fn($m) => $m['top']),
        //         'left' => $tree->filter(fn($m) => $m['left']),
        //         'footer' => $tree->filter(fn($m) => $m['footer']),
        //     ];
        // });
    }
}

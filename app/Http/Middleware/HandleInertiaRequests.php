<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Laravolt\Avatar\Facade as Avatar;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = Auth::user();

        return array_merge(parent::share($request), [

            // ----------------------------
            // AUTH USER DATA
            // ----------------------------
            'auth' => fn() => [
                'user' => $user ? [
                    'id'     => $user->id,
                    'name'   => $user->name,
                    'image'  => $user->image,
                    'roles'  => $user->roles->pluck('name'),
                    'unread' => $user->unreadNotifications()->count(),
                ] : null,
                'avatar' => $request->user()
                    ? Avatar::create($request->user()->name)->toBase64()
                    : null,
            ],

            // ----------------------------
            // FLASH MESSAGES
            // ----------------------------
            'flash' => [
                'status'  => fn() => $request->session()->get('status'),
                'error'   => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
            ],

            // ----------------------------
            // SITE SETTINGS
            // ----------------------------
            'site' => SiteSetting::first(),

            // ----------------------------
            // MENU BUILDER
            // ----------------------------
            'menus' => function () use ($user) {

                if (!$user) return [];

                $menus = $user->menus()->where('status', 1)->orderBy('order_by')->get();
                $grouped = $menus->groupBy('parent_id');

                $buildTree = function ($parentId = null) use (&$buildTree, $grouped, $user) {
                    return ($grouped[$parentId] ?? collect())->map(function ($menu) use ($buildTree, $user) {

                        $permissions = is_array($menu->permission_class)
                            ? $menu->permission_class
                            : json_decode($menu->permission_class ?? '[]', true);

                        $permissions = array_filter(array_map('trim', $permissions));

                        if (!empty($permissions) && !$user->hasAnyPermission($permissions)) {
                            return null;
                        }

                        return [
                            'id'       => $menu->id,
                            'title'    => $menu->title,
                            'route'    => $menu->route,
                            'icon'     => $menu->icon,
                            'top'      => $menu->top,
                            'left'     => $menu->left,
                            'footer'   => $menu->footer,
                            'children' => $buildTree($menu->id),
                        ];
                    })
                        ->filter()
                        ->values();
                };

                $tree = $buildTree();

                return [
                    'top'    => $tree->filter(fn($m) => $m['top']),
                    'left'   => $tree->filter(fn($m) => $m['left']),
                    'footer' => $tree->filter(fn($m) => $m['footer']),
                ];
            },

            // language 
            'locale' => app()->getLocale(),
            'translations' => function () {
                return [
                    'messages' => __('messages'),
                ];
            },
        ]);
    }
}

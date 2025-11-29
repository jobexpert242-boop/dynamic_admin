<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MenuController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('menu.show');

        $query = Menu::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Admin/Menu/Index', [
            'menuList' => $query->with('parent', 'children')->latest()->paginate(20)->withQueryString(),
            'allPermissions' => Permission::pluck('name'),
            'allMenus' => Menu::select('id', 'title')->get(),
            'search' => $request->search,
        ]);
    }

    public function store(Request $request)
    {
         $this->authorize('menu.create');

        $request->validate([
            'title' => 'required|string|max:191',
            'order_by' => 'required',
        ]);

        $menu = Menu::create($request->all());

        $adminUsers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin', 'sup_admin']))->get();
        foreach ($adminUsers as $user) {
            $user->menus()->attach($menu->id);
        }

        return redirect()->back()->with('status', 'Menu created and assigned to admin/sup_admin!');
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('menu.update');

        $request->validate([
            'title' => 'required|string|max:191',
            'order_by' => 'required',
        ]);

        $menu->update($request->all());

        return redirect()->back()->with('status', 'Menu updated!');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('menu.delete');

        $menu->delete();
        return redirect()->back()->with('status', 'Menu deleted!');
    }
}

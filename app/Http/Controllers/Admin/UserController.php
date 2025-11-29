<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('user.show');

        $query = User::with(['roles', 'permissions', 'menus'])->whereHas('roles');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $menus = Menu::select('id', 'title', 'permission_class')->get();
        $permissions = Permission::all();

        $groupedMenus = $menus->map(function ($menu) use ($permissions) {
            $assigned = is_array($menu->permission_class)
                ? collect($menu->permission_class)
                : collect(json_decode($menu->permission_class ?? '[]'));

            $related = $permissions->filter(function ($permission) use ($assigned) {
                return $assigned->contains($permission->name);
            })->values();

            return [
                'id' => $menu->id,
                'title' => $menu->title,
                'permissions' => $related,
            ];
        });

        return Inertia::render('Admin/User/Index', [
            'users' => $query->latest()->paginate(20)->withQueryString(),
            'roles' => Role::pluck('name'),
            'menuList' => $groupedMenus,
            'search' => $request->search,
        ]);
    }

    public function store(Request $request, User $user)
    {
        $this->authorize('user.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user?->id ?? 'null'),
            'password' => $user ? 'nullable|min:6' : 'required|min:6',
            'roles' => 'array',
            'permissions' => 'array',
            'menus' => 'array',
        ]);

        if ($request->id) {
            $user = User::findOrFail($request->id);
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
            if ($validated['password']) {
                $user->update(['password' => bcrypt($validated['password'])]);
            }
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);
        }

        $user->syncRoles($validated['roles']);

        if (collect($validated['roles'])->contains(fn($r) => in_array($r, ['admin', 'sup_admin']))) {
            $user->syncPermissions(Permission::pluck('name')->toArray());
            $user->menus()->sync(Menu::pluck('id')->toArray());
        } else {
            $user->syncPermissions($validated['permissions']);
            $user->menus()->sync($validated['menus']);
        }

        return redirect()->route('admin.users.index')->with('status', 'User saved successfully.');
    }


    public function destroy(User $user)
    {
        $this->authorize('user.delete');
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}

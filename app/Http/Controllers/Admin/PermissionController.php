<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\NewMessageNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('permission.show');

        $query = Permission::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Admin/Permission/Index', [
            'permissions' => $query->latest()->paginate(20)->withQueryString(),
            'search' => $request->search,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('permission.create');

        $request->validate(['name' => 'required|unique:permissions,name']);
        $permission = Permission::create(['name' => $request->name]);

        $roles = Role::whereIn('name', ['admin', 'sup_admin'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo($permission);
        }

        return redirect()->back()->with('status', 'Permission created and assigned to admin/sup_admin!');
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('permission.update');

        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);
        return redirect()->back()->with('status', 'Permission updated!');
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('permission.delete');
        
        $permission->delete();
        return redirect()->back()->with('status', 'Permission deleted!');
    }
}

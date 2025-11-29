<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
 
        $this->authorize('role.show');

        $query = Role::with('permissions');
        $permissions = Permission::all();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->role) {
            $query->where('name', $request->role);
        }

        return Inertia::render('Admin/Role/Index', [
            'roles' => $query->latest()->paginate(20)->withQueryString(),
            'permissions' => $permissions,
            'search' => $request->search,
            'role' => $request->role,
            'roleOptions' => Role::pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
         $this->authorize('role.create');

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->back()->with('status', 'Role created!');
    }

    public function update(Request $request, Role $role)
    {
         $this->authorize('role.update');

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->back()->with('status', 'Role updated!');
    }

    public function destroy(Role $role)
    {
         $this->authorize('role.delete');
         
        $role->delete();
        return redirect()->back()->with('status', 'Role deleted!');
    }
}

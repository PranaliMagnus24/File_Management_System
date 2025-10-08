<?php

namespace App\Http\Controllers\Backend\MasterSettings\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class RoleManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
             $roles = Role::query()->orderBy('created_at', 'desc');

             return DataTables::eloquent($roles)
                 ->addIndexColumn()
                 ->editColumn('name', fn($role) => ucfirst($role->name))
                 ->addColumn('action', function ($role) {
                     $editUrl = route('admin.roles.edit', $role->id);
                     $deleteUrl = route('admin.roles.destroy', $role->id);
                     $permUrl = route('admin.roles.permissions', $role->id);

                     $buttons = '<a href="' . $permUrl . '" class="btn btn-primary btn-sm">Add / Edit Role Permission</a> ';

                         $buttons .= '<a href="' . $editUrl . '" class="btn btn-success btn-sm edit-role"><i class="bi bi-pencil-square"></i></a> ';
                         $buttons .= '<a href="' . $deleteUrl . '" class="btn btn-danger btn-sm delete-confirm"><i class="bi bi-trash3-fill"></i></a>';

                     return $buttons;
                 })
                 ->rawColumns(['action'])
                 ->make(true);
         }
        return view('admin.Settings.RoleManagement.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permission' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->filled('permission')) {
            $role->syncPermissions($request->permission);
        }

        return redirect()->back()->with('success', 'Role created and permissions assigned successfully!');
    }
    public function edit($id)
{
    $role = Role::findOrFail($id);
    return response()->json([
        'status' => 'success',
        'data' => $role
    ]);
}

        public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);

        return redirect('roles')->with('success', 'Role updated successfully!');
    }

     public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect('roles')->with('success', 'Role deleted successfully!');
    }
    public function permissionToRole($id)
{
    $permissions = Permission::all();
    $role = Role::findOrFail($id);

    // Fetch assigned permission IDs
    $rolePermissions = DB::table('role_has_permissions')
        ->where('role_id', $role->id)
        ->pluck('permission_id')
        ->toArray();

    // Prepare modules and actions
    $modules = [];
    $actions = ['create', 'list', 'edit', 'update', 'view', 'delete'];

    foreach ($permissions as $permission) {
        $parts = explode('-', $permission->name);

        if (count($parts) === 2) {
            [$module, $action] = $parts;
            $modules[$module][$action] = [
                'id' => $permission->id,
                'checked' => in_array($permission->id, $rolePermissions)
            ];
        }
    }

    return view('admin.Settings.RoleManagement.add-permission', compact('role', 'modules', 'actions'));
}

public function updatePermissionToRole(Request $request, $id)
{
    $request->validate([
        'permission' => 'required|array'
    ]);

    $role = Role::findOrFail($id);

    $permissions = Permission::whereIn('id', $request->permission)->get();

    $role->syncPermissions($permissions);

    return redirect()->route('admin.roles.index')->with('success', 'Permissions added to role successfully!');
}

}

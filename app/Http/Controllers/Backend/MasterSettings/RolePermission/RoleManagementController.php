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
                ->editColumn('name', fn($role) => '<span class="">' . ucfirst($role->name) . '</span>')
                ->addColumn('action', function ($role) {
                    $permUrl = route('admin.roles.permissions', $role->id);

                    $buttons = '<a href="' . $permUrl . '" class="btn btn-primary btn-sm"><i class="bi bi-shield-lock me-1"></i>Permissions</a> ';

                     if (Auth::user()->can('role-update')) {
                    $buttons .= '<button class="btn btn-success btn-sm edit-role" data-id="' . $role->id . '"><i class="bi bi-pencil-square"></i></button> ';
                     }
                     if (Auth::user()->can('role-delete')) {

                    $buttons .= '<button class="btn btn-danger btn-sm delete-role" data-id="' . $role->id . '" data-name="' . $role->name . '"><i class="bi bi-trash3-fill"></i></button>';
                     }

                    return $buttons;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('admin.Settings.RoleManagement.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json(['success' => 'Role created successfully!']);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => 'Role updated successfully!']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            return response()->json(['error' => 'Cannot delete role. There are users assigned to this role.'], 422);
        }

        $role->delete();

        return response()->json(['success' => 'Role deleted successfully!']);
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

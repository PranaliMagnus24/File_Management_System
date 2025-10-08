<?php

namespace App\Http\Controllers\Backend\MasterSettings\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Validator;

class PermissionManagementController extends Controller
{
public function index(Request $request)
{
    if ($request->ajax()) {
        $permissions = Permission::latest()->get();
        return response()->json([
            'permissions' => $permissions
        ]);
    }

    // Return the view for normal requests
    return view('admin.Settings.PermissionManagement.index');
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permission = Permission::create([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully!',
                'permission' => $permission
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission.'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json([
                'success' => true,
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.'
            ], 404);
        }

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:permissions,id',
            'name' => 'required|string|unique:permissions,name,' . $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permission = Permission::findOrFail($request->id);
            $permission->update([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully!',
                'permission' => $permission
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permission.'
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permission = Permission::findOrFail($request->id);
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete permission.'
            ], 500);
        }
    }
}

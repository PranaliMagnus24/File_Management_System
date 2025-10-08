<?php

namespace App\Http\Controllers\Backend\MasterSettings\RolePermission;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($permissions)
                ->addIndexColumn()
                ->editColumn('name', function($permission) {
                    return $permission->name;
                })
                ->editColumn('created_at', function($permission) {
                    return $permission->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($permission) {
                    if (Auth::user()->can('permission-update')) {
                    $buttons = '<button class="btn btn-success btn-sm edit-permission" data-id="' . $permission->id . '"><i class="bi bi-pencil-square"></i></button> ';
                    }
                    if (Auth::user()->can('permission-delete')) {

                    $buttons .= '<button class="btn btn-danger btn-sm delete-permission" data-id="' . $permission->id . '" data-name="' . $permission->name . '"><i class="bi bi-trash3-fill"></i></button>';
                    }

                    return $buttons;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }

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
                'message' => 'Permission created successfully!'
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
                'message' => 'Permission updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permission.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);

            // Check if permission is assigned to any role
            if ($permission->roles()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete permission. It is assigned to one or more roles.'
                ], 422);
            }

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

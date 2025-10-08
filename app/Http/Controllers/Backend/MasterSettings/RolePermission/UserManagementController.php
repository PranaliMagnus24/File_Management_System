<?php

namespace App\Http\Controllers\Backend\MasterSettings\RolePermission;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->orderBy('created_at', 'desc');

            return DataTables::eloquent($users)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    return $user->getRoleNames()
                                ->map(function ($role) {
                                    return '<span class="badge bg-primary mx-1 text-white">' . $role . '</span>';
                                })->implode(' ');
                })
                ->addColumn('action', function ($user) {
                    $buttons = '';

                     if (Auth::user()->can('user-update')) {
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-success btn-sm edit-user" data-id="' . $user->id . '"><i class="bi bi-pencil-square"></i></a> ';
                     }

                     if (Auth::user()->can('user-delete')) {
                    $buttons .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-user" data-id="' . $user->id . '" data-name="' . $user->name . '"><i class="bi bi-trash3-fill"></i></a>';
                     }

                    return $buttons;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }
        $roles = Role::pluck('name','name')->all();
        $departments = Department::all();
        return view('admin.Settings.UserManagement.index', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'roles' => 'required',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|integer|digits_between:10|unique:users,phone',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'password' => Hash::make($request->password),
        ]);
        $user->syncRoles($request->roles);

        return response()->json(['success' => 'User created successfully!']);
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $userRoles = $user->roles->pluck('name')->toArray();
        $roles = Role::pluck('name', 'name')->all();

        return response()->json([
            'user' => $user,
            'userRoles' => $userRoles,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|max:20',
            'roles' => 'required',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->syncRoles($request->roles);

        return response()->json(['success' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id == Auth::id()) {
            return response()->json(['error' => 'You cannot delete your own account!'], 422);
        }

        $user->delete();

        return response()->json(['success' => 'User deleted successfully!']);
    }

}

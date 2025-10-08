<?php

namespace App\Http\Controllers\Backend\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($departments)
                ->addIndexColumn()
                ->editColumn('name', function ($department) {
                    return $department->name;
                })
                ->editColumn('created_at', function ($department) {
                    return $department->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($department) {
                    $buttons = '<button class="btn btn-success btn-sm edit-department" data-id="' . $department->id . '"><i class="bi bi-pencil-square"></i></button> ';
                    $buttons .= '<button class="btn btn-danger btn-sm delete-department" data-id="' . $department->id . '" data-name="' . $department->name . '"><i class="bi bi-trash3-fill"></i></button>';
                    return $buttons;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('admin.department.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:departments,name',
        ]);

        $department = Department::create(['name' => $request->name]);

        return response()->json(['success' => true, 'message' => 'Department created successfully!', 'department' => $department]);
    }

    public function edit($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found!'], 404);
        }

        return response()->json(['success' => true, 'department' => $department]);
    }

    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found!'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:departments,name,' . $id,
        ]);

        $department->update(['name' => $request->name]);

        return response()->json(['success' => true, 'message' => 'Department updated successfully!', 'department' => $department]);
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found!'], 404);
        }

        $department->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted successfully!']);
    }
}

<?php

namespace App\Http\Controllers\Backend\Racks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rack;
use Yajra\DataTables\Facades\DataTables;

class RackManagementController extends Controller
{
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $racks = Rack::query()->orderBy('created_at', 'desc');

            return DataTables::eloquent($racks)
                ->addIndexColumn()
                ->editColumn('name', function ($rack) {
                    return $rack->name;
                })
                ->editColumn('location', function ($rack) {
                    return $rack->location;
                })
                ->addColumn('action', function ($rack) {
                    $buttons = '<button class="btn btn-success btn-sm edit-rack" data-id="' . $rack->id . '"><i class="bi bi-pencil-square"></i></button> ';
                    $buttons .= '<button class="btn btn-danger btn-sm delete-rack" data-id="' . $rack->id . '" data-name="' . $rack->name . '"><i class="bi bi-trash3-fill"></i></button>';
                    return $buttons;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('admin.racks.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $rack = Rack::create(['name' => $request->name, 'location' => $request->location]);

        return response()->json(['success' => true, 'message' => 'Rack created successfully!', 'rack' => $rack]);

    }
    public function edit($id)
    {
        $rack = Rack::find($id);

        if (!$rack) {
            return response()->json(['success' => false, 'message' => 'Rack not found!'], 404);
        }

        return response()->json(['success' => true, 'rack' => $rack]);
    }

    public function update(Request $request, $id)
    {
        $rack = Rack::find($id);

        if (!$rack) {
            return response()->json(['success' => false, 'message' => 'Rack not found!'], 404);
        }

        $request->validate([
            'name' => 'required|string' . $id,
            'location' => 'nullable|string' . $id,
        ]);

        $rack->update(['name' => $request->name, 'location' => $request->location]);

        return response()->json(['success' => true, 'message' => 'Rack updated successfully!', 'rack' => $rack]);
    }

    public function destroy($id)
    {
        $rack = Rack::find($id);

        if (!$rack) {
            return response()->json(['success' => false, 'message' => 'Rack not found!'], 404);
        }

        $rack->delete();

        return response()->json(['success' => true, 'message' => 'Rack deleted successfully!']);
    }
}

<?php

namespace App\Http\Controllers;


use App\Models\Warehouse;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index()
    {
        $warehouses = Warehouse::all();

        return response()->json([
            'status' => 'success',
            'warehouses' => $warehouses,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'country' => ['required', 'string', 'max:255', 'unique:warehouses,country'],
            'location' => ['required', 'string'],
        ]);

        $warehouse = Warehouse::create([
            'country' => $request->country,
            'location' => $request->location,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $warehouse,
        ], 201);

    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'country' => ['required', 'string', 'max:255', 'unique:warehouses,country'],
            'location' => ['required', 'string'],
        ]);

        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }
        $warehouse->update([
            'country' => $request->country,
            'location' => $request->location,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'warehouse updated successfully.',
            'data' => $warehouse,
        ]);
    }

    public function show($id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'warehouse' => $warehouse,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Warehouse');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expenses_type;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpensesTypesController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses_type = Expenses_type::all();

        return response()->json([
            'status' => 'success',
            'expenses_types' => $expenses_type,
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
        log::info($request->all());
        $request->validate([
            'name' => ['required', 'string', 'unique:expenses_types,name'],
        ]);

        $expenses_type = Expenses_type::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $expenses_type,
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
            'name' => ['required', 'string', 'unique:expenses_types,name'],
        ]);

        $expenses_type = Expenses_type::find($id);

        if (!$expenses_type) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }
        $expenses_type->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice Company updated successfully.',
            'data' => $expenses_type,
        ]);
    }

    public function show($id)
    {
        $expenses_type = Expenses_type::find($id);

        if (!$expenses_type) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'expenses_type' => $expenses_type,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Expenses_type');
    }
}

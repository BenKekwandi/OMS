<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brands;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index()
    {
        $brands = Brands::all();

        return response()->json([
            'status' => 'success',
            'brands' => BrandResource::collection($brands),
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
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
        ]);

        $brand = Brands::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $brand,
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
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
        ]);

        $brand = Brands::find($id);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }
        $brand->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Brand updated successfully.',
            'data' => $brand,
        ]);
    }

    public function show($id)
    {
        $brand = Brands::find($id);

        if (!$brand) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'brand' => $brand,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Brands');
    }
}

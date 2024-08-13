<?php

namespace App\Http\Controllers;

use App\Models\Models;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $models = Models::all();

        return response()->json([
            'status' => 'success',
            'models' => $models,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'reference' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('models', $imageName, 'public');
        }

        $model = Models::create([
            'reference' => $request->reference,
            'image' => $path ? $path : null,
            'brand_id' => $request->brand_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Model created successfully.',
            'data' => $model,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user->hasrole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        Log::info($request->all());

        $model = Models::find($id);

        if (! $model) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $request->validate([
            'reference' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
            $image = $request->file('image');
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('models', $imageName, 'public');
        }

        $model->update([
            'reference' => $request->reference,
            'image' => $request->hasFile('image') ? $path : $model->image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Model updated successfully.',
            'data' => $model,
        ]);
    }

    public function show($id)
    {
        $model = Models::find($id);

        if (! $model) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'model' => $model,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Models');
    }

    public function modelByBrand($id)
    {
        $models = Models::where('brand_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'models' => $models,
        ]);
    }
}

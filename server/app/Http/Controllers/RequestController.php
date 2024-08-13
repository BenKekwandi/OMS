<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $requests = Requests::all();

        return response()->json([
            'status' => 'success',
            'data' => $requests,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'brand_id' => 'required|integer',
            'model_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|integer',
        ]);

        $reqst = Requests::create([
            'brand_id' => $request->brand_id,
            'model_id' => $request->model_id,
            'customer_id' => $request->customer_id,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $reqst,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'brand_id' => 'required|integer',
            'model_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'deadline' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|integer',
        ]);

        $reqst = Requests::find($id);

        if (! $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $reqst->update([
            'brand_id' => $request->brand_id,
            'model_id' => $request->model_id,
            'customer_id' => $request->customer_id,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $reqst,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $reqst = Requests::find($id);

        if (! $reqst) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $reqst,
        ]);

    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request = Requests::find($id);

        if (! $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $request->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ]);
    }

    public function matchingOffers($id)
    {
        $req = Requests::find($id);

        if (! $req) {

            return response()->json([
                'status' => 'Error',
                'message' => 'Record Not found',
            ], 404);
        }
        $brand_id = $req->brand_id;
        $model_id = $req->model_id;

        $matchingOffers = Offers::where(['brand_id' => $brand_id, 'model_id' => $model_id])->get();

        if ($matchingOffers->isEmpty()) {
            return response()->json([
                'status' => 'Success',
                'number_of_matches' => 0,
                'message' => 'No matching offers found',
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'number_of_matches' => $matchingOffers->count(),
            'matching_Offers' => $matchingOffers,
        ]);
    }
}

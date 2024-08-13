<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
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
        $countries = Country::all();

        return response()->json([
            'status' => 'success',
            'countries' => $countries,
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
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'vat' => ['required', 'numeric'],
        ]);

        $country = Country::create([
            'name' => $request->name,
            'vat' => $request->vat,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $country,
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
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'vat' => ['required', 'numeric'],

        ]);

        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }
        $country->update([
            'name' => $request->name,
            'vat' => $request->vat,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'country updated successfully.',
            'data' => $country,
        ]);
    }

    public function show($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'country' => $country,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Country');
    }
}

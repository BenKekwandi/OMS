<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficeAddressRequest;
use App\Http\Resources\OfficeAddressResource;
use App\Models\OfficeAddress;
use Illuminate\Http\Request;

class OfficeAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OfficeAddressResource::collection(OfficeAddress::all());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfficeAddressRequest $request)
    {
        $officeAddress = new OfficeAddressResource(OfficeAddress::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $officeAddress,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeAddress $officeAddress)
    {
        return new OfficeAddressResource($officeAddress);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfficeAddressRequest $request, OfficeAddress $officeAddress)
    {
        $officeAddress->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $officeAddress,
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeAddress $officeAddress)
    {
        $officeAddress->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ], 201);
    }
}

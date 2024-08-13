<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelInvoiceRequest;
use App\Http\Resources\LabelInvoiceResource;
use App\Models\LabelInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class LabelInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return LabelInvoiceResource::collection(LabelInvoice::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabelInvoiceRequest $request)
    {
        $labelInvoice = new LabelInvoiceResource(LabelInvoice::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $labelInvoice,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LabelInvoice $labelInvoice): LabelInvoiceResource
    {
        return new LabelInvoiceResource($labelInvoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabelInvoiceRequest $request, LabelInvoice $labelInvoice)
    {
        $labelInvoice->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $labelInvoice,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LabelInvoice $labelInvoice)
    {
        $labelInvoice->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ], 201);
    }
}

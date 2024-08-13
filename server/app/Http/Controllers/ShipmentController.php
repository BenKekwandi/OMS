<?php

namespace App\Http\Controllers;

use App\Events\ShipmentAddress;
use App\Http\Requests\ShipmentRequest;
use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\helpers;


class ShipmentController extends Controller
{

    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }
    /**
     * Display all Shipments.
     */
    public function index(): AnonymousResourceCollection
    {
        return ShipmentResource::collection(Shipment::all());
    }

    /**
     * Store Shipment.
     */
    public function store(ShipmentRequest $request)
    {
        $shipment = new ShipmentResource(Shipment::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $shipment,
        ], 201);
    }

    /**
     * Display a Shipment.
     */
    public function show(Shipment $shipment): ShipmentResource
    {
        return new ShipmentResource($shipment->load('orders'));
    }

    /**
     * Update Shipment.
     */
    public function update(ShipmentRequest $request, Shipment $shipment)
    {
        $shipment->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $shipment,
        ], 201);
    }

    /**
     * Remove Shipment.
     */
    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'Shipment');
    }
}

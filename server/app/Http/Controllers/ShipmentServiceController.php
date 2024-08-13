<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentServiceRequest;
use App\Http\Resources\ShipmentServiceResource;
use App\Models\ShipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\helpers;
use Log;


class ShipmentServiceController extends Controller
{

    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }


    /**
     * Display all Shipments Service.
     */
    public function index(): AnonymousResourceCollection
    {
        return ShipmentServiceResource::collection(ShipmentService::all());
    }

    /**
     * Store Shipment Service.
     */
    public function store(ShipmentServiceRequest $request): JsonResponse
    {
        $shipmentService = new ShipmentServiceResource(ShipmentService::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $shipmentService,
        ], 201);
    }

    /**
     * Display a Shipment Service.
     */
    public function show(ShipmentService $shipmentService): ShipmentServiceResource
    {
        return new ShipmentServiceResource($shipmentService);
    }

    /**
     * Update Shipment Service.
     */
    public function update(ShipmentServiceRequest $request, ShipmentService $shipmentService): JsonResponse
    {
        $shipmentService->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $shipmentService,
        ], 201);
    }

    /**
     * Remove Shipment Service.
     */
    public function destroy(Request $request)
    {
        Log::info($request);
        return $this->helpers->deactivate($request, ['admin'], 'ShipmentService');
    }
}

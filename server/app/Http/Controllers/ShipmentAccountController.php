<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentAccountRequest;
use App\Http\Resources\ShipmentAccountResource;
use App\Models\ShipmentAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\helpers;


class ShipmentAccountController extends Controller
{

    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }
    /**
     * Display all Shipments Account.
     */
    public function index(): AnonymousResourceCollection
    {
        return ShipmentAccountResource::collection(ShipmentAccount::all());
    }

    /**
     * Store Shipment Account.
     */
    public function store(ShipmentAccountRequest $request): JsonResponse
    {
        $shipmentAccount = new ShipmentAccountResource(ShipmentAccount::create($request->validated()));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $shipmentAccount,
        ], 201);
    }

    /**
     * Display a Shipment Service.
     */
    public function show(ShipmentAccount $shipmentAccount): ShipmentAccountResource
    {
        return new ShipmentAccountResource($shipmentAccount);
    }

    /**
     * Update Shipment Service.
     */
    public function update(ShipmentAccountRequest $request, ShipmentAccount $shipmentAccount): JsonResponse
    {
        $shipmentAccount->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $shipmentAccount,
        ], 201);
    }

    /**
     * Remove Shipment Service.
     */
    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin'], 'ShipmentAccount');
    }

    public function accountByService($id)
    {
        $shipmentAccount = ShipmentAccount::where('shipment_service_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $shipmentAccount,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderShipmentRequest;
use App\Http\Resources\OrderShipmentResource;
use App\Models\Orders;
use App\Models\OrderShipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Log;

class OrderShipmentController extends Controller
{
    /**
     * Display all Order Shipments.
     */
    public function index(): AnonymousResourceCollection
    {
        return OrderShipmentResource::collection(OrderShipment::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderShipmentRequest $request)
    {
        $orderShipments = $request->validated();
        $createdOrderShipments = [];
        $existingOrderShipments = [];
        $noneAcceptableOrders = [];
        Log::info($request->all());

        // Collect all order IDs
        foreach ($orderShipments as $orderShipmentData) {
            $orderShipment = OrderShipment::where('order_id', $orderShipmentData['order_id'])->exists();
            if ($orderShipment) {
                $existingOrderShipments[] = $orderShipmentData['order_id'];
                continue;
            }
            $order = Orders::find($orderShipmentData['order_id']);
            if ($order) {
                if ($order->supplier_id) {
                    $order->shipment_id = $orderShipmentData['shipment_id'];
                    $order->status = 11;
                    $order->save();
                } else {
                    $noneAcceptableOrders[] = $order;
                    continue;
                }
            }
            $createdOrderShipments[] = new OrderShipmentResource(OrderShipment::create($orderShipmentData));
        }

        if ($noneAcceptableOrders) {
            $noneAcceptableOrdersId = implode(', ', $noneAcceptableOrders);
            return response()->json([
                'status' => 'warning',
                'message' => 'Records with order IDs ' . $noneAcceptableOrdersId . ' Does not have Offers.',
            ], 201);
        }

        if ($existingOrderShipments) {
            $existingOrderShipmentIdsString = implode(', ', $existingOrderShipments);
            return response()->json([
                'status' => 'warning',
                'message' => 'Records with order IDs ' . $existingOrderShipmentIdsString . ' already have shipments.',
            ], 201);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Records created successfully.',
            'data' => $createdOrderShipments,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(OrderShipment $orderShipment)
    {
        return new OrderShipmentResource($orderShipment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderShipmentRequest $request, OrderShipment $orderShipment)
    {
        $orderShipment->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $orderShipment,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderShipment $orderShipment)
    {
        $orderShipment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ], 201);
    }

    public function deleteOrder(OrderShipmentRequest $request)
    {

        $orderShipments = $request->all();
        foreach ($orderShipments as $orderShipmentData) {

            $order = Orders::find($orderShipmentData['order_id']);
            if ($order) {
                $order->shipment_id = null;
                $order->status = 9;
                $order->save();
            }
            $orderShipment = OrderShipment::where(
                'order_id',
                $orderShipmentData['order_id']
            )->first();
            $orderShipment->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Records deleted successfully.',
        ], 201);


    }

    public function getOrders($shipmentId)
    {
        $orderShipments = OrderShipment::where('shipment_id', $shipmentId)->get();
        $orderIds = $orderShipments->pluck('order_id')->unique();

        $orders = Orders::with(
            'brand:id,name',
            'customer.country',
            'supplier.brands',
            'supplier.country',
            'supplier.pm:id,name',
            'offer',
            'shipment.label.label_invoice'
        )->whereIn('id', $orderIds)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ], 200);
    }
}

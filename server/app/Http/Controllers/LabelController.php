<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Http\Resources\LabelResource;
use App\Models\Label;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Log;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return LabelResource::collection(Label::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabelRequest $request)
    {


        // $shipment = Shipment::find($request->shipment_id);
        // $shipment->load('orders');
        // $apiKey = 'f0cd0025-6cf5-46a3-abe1-8ccdb3a3400a';
        // $url = 'https://sandbox-api.aftership.com/postmen/v3/labels';
        // $items = [];


        // foreach ($shipment->orders as $order) {
        //     $items[] = [
        //         "description" => "{$order->brand->name}/{$order->reference_number}",
        //         "quantity" => 1,
        //         "price" => [
        //             "currency" => "USD",
        //             "amount" => $order->proposal->sell_price
        //         ],
        //         "weight" => [
        //             "unit" => "kg",
        //             "value" => $shipment->box_weight
        //         ],
        //         "sku" => $order->reference_number
        //     ];
        // }
        // $labelData = [
        //     "service_type" => $shipment->shipment_account->shipment_services->title,
        //     "shipper_account" => [
        //         "id" => $shipment->shipment_account->postmen_id
        //     ],
        //     "shipment" => [
        //         "ship_from" => [
        //             "contact_name" => $shipment->ship_from_id->contact_name,
        //             "company_name" => $shipment->ship_from_id->company,
        //             "street1" => $shipment->ship_from_id->street1,
        //             "city" => $shipment->ship_from_id->city,
        //             "state" => $shipment->ship_from_id->state,
        //             "postal_code" => $shipment->ship_from_id->postal_code,
        //             "country" => $shipment->ship_from_id->country,
        //             "phone" => $shipment->ship_from_id->phone,
        //             "email" => $shipment->ship_from_id->email
        //         ],
        //         "ship_to" => [
        //             "contact_name" => $shipment->ship_to_id->contact_name,
        //             "company_name" => $shipment->ship_to_id->company,
        //             "street1" => $shipment->ship_to_id->street1,
        //             "city" => $shipment->ship_to_id->city,
        //             "state" => $shipment->ship_to_id->state,
        //             "postal_code" => $shipment->ship_to_id->postal_code,
        //             "country" => $shipment->ship_to_id->country,
        //             "phone" => $shipment->ship_to_id->phone,
        //             "email" => $shipment->ship_to_id->email
        //         ]
        //     ],
        //     "parcels" => [
        //         [
        //             "box_type" => "custom",
        //             "dimension" => [
        //                 "width" => $shipment->box_width,
        //                 "height" => $shipment->box_height,
        //                 "depth" => $shipment->box_depth,
        //                 "unit" => "cm"
        //             ],

        //             "items" => $items
        //         ]
        //     ]
        // ];

        // $options = [
        //     'http' => [
        //         'method' => 'POST',
        //         'header' => [
        //             "Content-Type: application/json",
        //             "as-api-key: $apiKey"
        //         ],
        //         'content' => json_encode($labelData)
        //     ]
        // ];

        // // Create stream context
        // $context = stream_context_create($options);

        // // Fetch data from API
        // $data = file_get_contents($url, false, $context);

        // // Handle response
        // if ($data === false) {
        //     // Handle error
        //     return 'Error fetching data';
        // }

        // $dataArray = json_decode($data, true);

        // Output the response data (optional)

        $shipment = Shipment::findOrFail($request->shipment_id);
        $shipment->update([
            'status' => 2
        ]);
        $kind = $shipment->automatic_shipping ? 1 : 2;

        $request->merge(['kind' => $kind]);
        $validatedData = $request->validated();
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('labels', 'public');
            $validatedData['file'] = $filePath;
        }
        $label = new LabelResource(Label::create($validatedData));


        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $label,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label): LabelResource
    {
        return new LabelResource($label);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabelRequest $request, Label $label)
    {
        $label->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $label,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {

        $shipment = Shipment::findOrFail($label->shipment_id);
        $shipment->load('orders');
        $shipment->update(['status' => 1]);
        
        foreach ($shipment->orders as $order) {
            $order->status = 9;
            $order->save();
        }
        $label->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ], 201);
    }

    public function stepBack(Label $label)
    {
        $shipment = Shipment::findOrFail($label->shipment_id);

        if ($shipment->status === 'Collected') {
            $shipment->update([
                'status' => 2,
                'collected_at' => null
            ]);

        } elseif ($shipment->status === 'Delivered' || $shipment->status === 'Delivered To Customer') {
            $shipment->load('orders');
            foreach ($shipment->orders as $order) {
                $order->status = 11;
                $order->save();
            }
            $shipment->update([
                'status' => 3,
                'delivered_at' => null
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Stepped back successfully.',
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\OfferExport;
use App\Imports\OfferImport;
use App\Models\Brands;
use App\Models\Country;
use App\Models\Models;
use App\Models\Offers;
use App\Models\Orders;
use App\Models\Supplier;
use App\Models\User;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class OfferController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $offers = Offers::with('brand:id,name')->with(['supplier.country'])->get();

        foreach ($offers as $offer) {
            $user = User::find($offer->supplier->user_id);
            $offer['pm'] = $user->name;
        }

        foreach ($offers as $offer) {
            $offer['matches'] = Orders::where([
                'brand_id' => $offer['brand_id'],
                'reference_number' => $offer['reference_number'],
            ])->whereIn('status', [1, 2])->count();
        }

        return response()->json([
            'status' => 'success',
            'offers' => $offers,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        Log::info($request->all());

        $request->validate([
            'brand_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'reference_number' => 'required|string',
            'order_days' => 'nullable|integer',
            'discount' => 'required|numeric',
            'net_price' => 'numeric',
            'rrp_price' => 'required|numeric',
            'rrp_explanation' => 'nullable|string',
            'other_features' => 'nullable|string',
            'location' => 'nullable|integer',
            'serial_number' => 'nullable|string',
            'availability' => 'required|integer',
        ]);

        $path = null;
        if ($request->image) {
            $parsedUrl = parse_url($request->image);
            $paths = $parsedUrl['path'];
            $path = substr($paths, strpos($paths, '/storage/') + strlen('/storage/'));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('models', $imageName, 'public');
        }

        $model = Models::where(['brand_id' => $request->brand_id, 'reference' => $request->reference_number])->first();
        if (!$model) {
            Models::create([
                'brand_id' => $request->brand_id,
                'reference' => $request->reference_number,
                'image' => $path ? $path : null,
            ]);
        } elseif ($path && $model->image != $path) {
            Storage::disk('public')->delete($model->image);
            $model->update(['image' => $path]);
        }
        $offer = Offers::create([
            'brand_id' => $request->brand_id,
            'supplier_id' => $request->supplier_id,
            'reference_number' => $request->reference_number,
            'order_days' => $request->order_days ? $request->order_days : 0,
            'discount' => $request->discount,
            'net_price' => $request->net_price,
            'rrp_price' => $request->rrp_price,
            'image' => $path ? $path : null,
            'other_features' => $request->other_features,
            'warehouse_id' => $request->location,
            'serial_number' => $request->serial_number,
            'availability' => $request->availability,
            'status' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $offer,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        Log::info($request->all());

        $request->validate([
            'brand_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'reference_number' => 'required|string',
            'order_days' => 'nullable|integer',
            'discount' => 'required|numeric',
            'net_price' => 'numeric',
            'rrp_price' => 'required|numeric',
            'rrp_explanation' => 'nullable|string',
            'other_features' => 'nullable|string',
            'location' => 'nullable|integer',
            'serial_number' => 'nullable|string',
        ]);

        $offer = Offers::find($id);

        if (!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $path = null;
        if ($request->image) {
            $parsedUrl = parse_url($request->image);
            $paths = $parsedUrl['path'];
            $path = substr($paths, strpos($paths, '/storage/') + strlen('/storage/'));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if (!is_string($image)) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('models', $imageName, 'public');
            }
        }

        $model = Models::where(['brand_id' => $offer->brand_id, 'reference' => $offer->reference_number])->first();
        if ($model) {
            $data = [
                'brand_id' => $request->brand_id,
                'reference' => $request->reference_number,
            ];
            if ($path && $model->image != $path) {
                $data['image'] = $path;
            }
            $model->update($data);
        }

        $offerData = [
            'brand_id' => $request->brand_id,
            'supplier_id' => $request->supplier_id,
            'reference_number' => $request->reference_number,
            'order_days' => $request->order_days,
            'discount' => $request->discount,
            'net_price' => $request->net_price,
            'rrp_price' => $request->rrp_price,
            'rrp_explanation' => $request->rrp_explanation,
            'other_features' => $request->other_features,
            'location' => $request->location,
            'serial_number' => $request->serial_number,
        ];

        if (!is_string($request->file('image')) && $path) {
            $offerData['image'] = $path;
        }
        if (!is_string($request->availability)) {
            $offerData['availability'] = $request->availability;
        }

        $offer->update($offerData);

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $offer,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $offer = Offers::find($id);

        if (!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $offer,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin', 'pm'], 'Offers');
    }

    public function getNetPrice(Request $request)
    {

        $request->validate([
            'rrp_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'supplier_id' => 'required|integer',
        ]);
        $supplier = Supplier::find($request->supplier_id);

        if (!$supplier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }
        $rrp = floatval($request->input('rrp_price'));
        $discount = floatval($request->input('discount'));
        $vat = floatval(Country::find($supplier->country_id)->vat);
        $netPrice = $rrp - (($rrp * $vat) + ($rrp * $discount * 0.01));

        return response()->json([
            'status' => 'success',
            'net_price' => $netPrice,
        ], 200);

    }

    public function matchingOrders($id)
    {
        $offer = Offers::find($id);

        if (!$offer) {

            return response()->json([
                'status' => 'Error',
                'message' => 'Record Not found',
            ], 404);
        }

        $brand_id = $offer->brand_id;
        $reference_number = $offer->reference_number;

        $matchingOrders = Orders::where([
            'brand_id' => $brand_id,
            'reference_number' => $reference_number,
        ])->whereIn('status', [1, 2])->get();

        if ($matchingOrders->isEmpty()) {
            return response()->json([
                'status' => 'Success',
                'number_of_matches' => 0,
                'message' => 'No matching Order found',
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'offer_id' => $offer->id,
            'brand' => Brands::find($offer->brand_id)->name,
            'reference_number' => $offer->reference_number,
            'number_of_matches' => $matchingOrders->count(),
            'matching_orders' => $matchingOrders,
        ]);
    }

    public function reset(Request $request)
    {
        return $this->helpers->reactivate($request, ['admin', 'pm'], 'Offers');

    }

    public function export(Request $request)
    {
        return Excel::download(new OfferExport, 'offers.csv');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs('imported', $originalName, 'public');
            $fullPath = storage_path('app/public/' . $path);

            try {
                Excel::import(new OfferImport, $fullPath);
                return response()->json([
                    'status' => 'Success',
                    'message' => 'File imported successfully'
                ]);
            } catch (\Exception $e) {
                Log::error('Import failed: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Import failed',
                ], 500);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Import failed file is not valid',
        ], 400);

    }

}

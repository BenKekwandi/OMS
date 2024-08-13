<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Models\Supplier_brand;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm') || $user->hasrole('accounting'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $suppliers = $user->hasRole('pm') ?
            Supplier::where('user_id', $user->id)->get() : Supplier::all();

        return response()->json([
            'status' => 'success',
            'suppliers' => SupplierResource::collection($suppliers),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        Log::info($request->all());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Supplier::class],
            'phone' => 'nullable|string',
            'country_id' => 'required|integer',
            'address' => 'nullable|string',
            'primary_name' => 'nullable|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'invoice_delivery_rules' => 'nullable|string',
            'tax' => 'nullable|string',
            'is_credit' => 'nullable|boolean',
        ]);
        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'primary_name' => $request->primary_name,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'invoice_delivery_rules' => $request->invoice_delivery_rules,
            'tax' => $request->tax,
            'is_credit' => $request->is_credit,
            'user_id' => $user->id,
        ]);

        $brands = $request->input('brands');

        if ($brands) {
            foreach ($brands as $brand) {
                Supplier_brand::create([
                    'supplier_id' => $supplier->id,
                    'brand_id' => $brand,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $supplier,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        Log::info($request->all());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique('suppliers')->ignore($id)],
            'phone' => 'nullable|string',
            'country_id' => 'required|integer',
            'address' => 'nullable|string',
            'primary_name' => 'nullable|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'invoice_delivery_rules' => 'nullable|string',
            'tax' => 'nullable|string',
            'is_credit' => 'nullable|boolean',
        ]);

        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'primary_name' => $request->primary_name,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'invoice_delivery_rules' => $request->invoice_delivery_rules,
            'tax' => $request->tax,
            'is_credit' => $request->is_credit,
        ]);

        $brands = $request->input('brands');

        if ($brands) {
            Supplier_brand::where('supplier_id', $id)->delete();
            foreach ($brands as $brand) {
                Supplier_brand::create([
                    'supplier_id' => $id,
                    'brand_id' => $brand,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => new SupplierResource($supplier),
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'supplier' => new SupplierResource($supplier),
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin', 'pm'], 'Supplier');
    }

    public function transfer(Request $request, $id)
    {
        return $this->helpers->transfer($request, $id, 'Supplier');

    }

    public function export(Request $request)
    {
        return Excel::download(new SupplierExport(Auth::user()->id), 'suppliers.csv');
    }

    public function import(Request $request)
    {

    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    protected $helpers;

    public function __construct(helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $customers = $user->hasRole('sm') ?
            Customer::where('user_id', $user->id)
                ->with('country:id,name')
                ->get() :
            Customer::with('country:id,name')
                ->get();

        
        return response()->json([
            'status' => 'success',
            'customers' => CustomerResource::collection($customers),
        ]);
    }

    public function store(CustomerRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();
        $data = array_merge($validatedData, ['user_id' => $user->id,]);
        $customer = new CustomerResource(Customer::create($data));

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $customer,
        ], 201);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $customer,
        ]);
    }

    public function show(Customer $customer)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $customer = new CustomerResource($customer);

        return response()->json([
            'status' => 'success',
            'customers' => $customer,
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin', 'sm'], 'Customer');
    }

    public function transfer(Request $request, $id)
    {
        return $this->helpers->transfer($request, $id, 'Customer');

    }

    public function export(Request $request)
    {
        return Excel::download(new CustomerExport(Auth::user()->id), 'customers.csv');
    }

    public function import(Request $request)
    {

    }
}

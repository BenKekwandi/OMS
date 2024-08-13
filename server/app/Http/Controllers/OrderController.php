<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Exports\AccOrderExport;
use App\Http\Resources\OrderResource;
use App\Imports\OrderImport;
use App\Models\Brands;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Models;
use App\Models\Offers;
use App\Models\Orders;
use App\Models\Proposal;
use App\Models\Shipment;
use App\Services\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
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
        if (($user->hasrole('accounting') || $user->hasrole('logistic'))) {
            $orders = Orders::with(
                'brand:id,name',
                'customer.country',
                'supplier.brands',
                'supplier.country',
                'offer',
                'expenses',
                'shipment.shipment_account.shipment_services',
                'shipment.label.label_invoice'
            )->whereNotIn('status', [1, 2, 3, 11, 12])->get();
        } else {
            $orders = Orders::with(
                'brand:id,name',
                'customer.country',
                'supplier.country',
                'offer',
                'expenses',
                'shipment.shipment_account.shipment_services',
                'shipment.label.label_invoice'
            )->get();
        }

        $customers = $user->hasRole('sm') ? Customer::with('country')->where('user_id', $user->id)->get() : Customer::with('country')->get();

        $ordersByCustomer = [];

        foreach ($customers as $customer) {
            $customerOrders = $orders->where('customer_id', $customer->id);
            foreach ($customerOrders as $order) {
                $ordersByCustomer[] = $order;
            }
        }

        $orders = $user->hasRole('sm') ? $ordersByCustomer : $orders;

        foreach ($orders as $order) {
            $matches = 0;
            $offers = Offers::with('supplier.country')->where([
                'brand_id' => $order->brand_id,
                'reference_number' => $order->reference_number,
            ])->whereIn('status', [1, 2])->get();
            foreach ($offers as $offer) {
                if ($order->customer->country_id != $offer->supplier->country_id)
                    $matches++;
            }
            if ($matches > $order->matches && $order->is_read == true) {
                $order->is_read = false;
            }
            $order->matches = $matches;
            $order->save();
        }

        foreach ($orders as $order) {
            $proposal = Proposal::where('order_id', $order->id)->where('offer_id', $order->offer_id)->first();
            $invoices = Invoice::with('payments')->where('order_id', $order->id)->get();
            foreach ($invoices as $invoice) {
                if (($user->hasRole('admin') || $user->hasRole('accounting') || $user->hasRole('logistic'))) {
                    if ($invoice->is_customer) {
                        $order->customer_invoice = $invoice;
                    }
                    $order->supplier_invoice = $invoice;
                }
            }

            if ($proposal) {
                $order->proposal = $proposal;
                $order->total_expenses = $order->expenses()->sum('amount');
                $order->shipping_cost = $order->expenses()->where('expenses_type_id', 4)->sum('amount');
            }
        }

        return response()->json([
            'status' => 'success',
            'orders' => $orders,
        ]);
    }

    public function accountingOrders()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || ($user->hasrole('accounting')))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $orders = Orders::with(
            'brand:id,name',
            'customer.country',
            'supplier.brands',
            'supplier.country',
            'supplier.pm:id,name',
            'offer',
            'expenses',
            'shipment.shipment_account.shipment_services',
            'shipment.label.label_invoice'
        )->whereNotIn('status', [1, 2, 3, 13, 14])->get();

        foreach ($orders as $order) {
            $proposal = Proposal::where('order_id', $order->id)->where('offer_id', $order->offer_id)->first();
            $invoices = Invoice::with('payments')->where('order_id', $order->id)->get();

            $supplierInvoice = null;
            $customerInvoice = null;

            foreach ($invoices as $invoice) {
                if ($user->hasRole('admin') || $user->hasRole('accounting')) {
                    if ($invoice->is_customer) {
                        $customerInvoice = $invoice;
                    } else {
                        $supplierInvoice = $invoice;
                    }
                }
            }
            if ($supplierInvoice && $customerInvoice && !$order->shipment) {
                if ($supplierInvoice->is_paid && $customerInvoice->is_paid) {
                    $order->status = 9;
                    $order->save();
                }
            }
            $order->supplier_invoice = $supplierInvoice;
            $order->customer_invoice = $customerInvoice;


            if ($proposal) {
                $order->proposal = $proposal;
                $expenses = $order->expenses()->sum('amount');
                $order->profit = $order->proposal->sell_price - $order->offer->net_price;
                $order->total_expenses = $expenses;
            }
            if (!$order->shipment_id) {
                $order->existing_shipment = true;
            } else
                $order->existing_shipment = false;
        }

        return response()->json([
            'status' => 'success',
            'orders' => $orders,
        ]);
    }

    public function logisticOrders()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || ($user->hasrole('logistic')))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $orders = Orders::with(
            'brand:id,name',
            'customer',
            'supplier',
            'offer',
        )->where('status', 9)->get();

        return response()->json([
            'status' => 'success',
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        Log::info($request->all());

        $request->validate([
            'brand_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reference_number' => 'string|max:255',
            'other_features' => 'nullable|string|max:255',
            'deadline' => 'required|date',
        ]);
        $customer = Customer::find($request->customer_id)->name;

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
        $order = Orders::create([
            'brand_id' => $request->brand_id,
            'customer_id' => $request->customer_id,
            'reference_number' => $request->reference_number,
            'image' => $path ? $path : null,
            'other_features' => $request->other_features,
            'deadline' => $request->deadline,
            'name_for_warranty' => $customer,
            'status' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $order,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }
        Log::info($request->all());

        $request->validate([
            'brand_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'reference_number' => 'string|max:255',
            'other_features' => 'nullable|string|max:255',
            'deadline' => 'required|date',
        ]);

        $order = Orders::find($id);
        if (!$order) {
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
        Log::info('First ' . $path);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if (!is_string($image)) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('models', $imageName, 'public');
            }
        }
        Log::info('Second ' . $path);

        $model = Models::where(['brand_id' => $order->brand_id, 'reference' => $order->reference_number])->first();
        if (!$model) {
            Models::create([
                'brand_id' => $request->brand_id,
                'reference' => $request->reference_number,
                'image' => $path ? $path : null,
            ]);
            if ($path && $model->image != $path) {
                $data['image'] = $path;
            }
            $model->update($data);
        }

        $orderData = [
            'brand_id' => $request->brand_id,
            'customer_id' => $request->customer_id,
            'reference_number' => $request->reference_number,
            'other_features' => $request->other_features,
            'deadline' => $request->deadline,
            'name_for_warranty' => $request->name_for_warranty,
        ];
        if (!is_string($request->file('image')) && $path) {
            $orderData['image'] = $path;
        }
        $order->update($orderData);

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $order,
        ]);
    }

    public function show(Orders $order)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => new OrderResource($order),
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->helpers->deactivate($request, ['admin', 'sm'], 'Orders');
    }

    public function matchingOffers($id)
    {
        $order = Orders::find($id);

        if (!$order) {

            return response()->json([
                'status' => 'Error',
                'message' => 'Record Not found',
            ], 404);
        }
        $brand_id = $order->brand_id;
        $reference_number = $order->reference_number;

        $matchingOffers = Offers::where([
            'brand_id' => $brand_id,
            'reference_number' => $reference_number,
        ])->whereIn('status', [1, 2])->with('supplier:id,name')->get();

        if ($matchingOffers->isEmpty()) {
            return response()->json([
                'status' => 'Success',
                'number_of_matches' => 0,
                'message' => 'No matching offers found',
            ]);
        }
        $order->update([
            'is_read' => true,
        ]);

        return response()->json([
            'status' => 'Success',
            'order_id' => $order->id,
            'brand' => Brands::find($order->brand_id)->name,
            'reference_number' => $order->reference_number,
            'number_of_matches' => $matchingOffers->count(),
            'matching_offers' => $matchingOffers,
        ], 200);
    }

    public function confirm(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('accounting'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        Log::info($request->all());

        $request->validate([
            'amount' => 'required|numeric',
            'invoice_company_id' => 'nullable|integer',
            'invoice_number' => 'nullable|string',
            'invoicing_date' => 'nullable|date',
            'payment_deadline' => 'nullable',
            'is_real' => 'required',
        ]);

        $order = Orders::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $path = null;
        if ($request->file('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('invoices', $fileName, 'public');
        }

        $is_real = $request->is_real === 'true' ? true : false;

        Invoice::create([
            'order_id' => $id,
            'file' => $path ? $path : null,
            'amount' => $request->amount,
            'invoice_company_id' => $request->invoice_company_id,
            'invoice_number' => $request->invoice_number,
            'invoicing_date' => $request->invoicing_date,
            'payment_deadline' => $request->payment_deadline,
            'is_real' => $is_real,
        ]);

        if ($order->status != 9) {
            $order->update([
                'status' => 5,
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Order successfully confirmed',
        ], 200);
    }

    public function customerInvoice(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('accounting'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'amount' => 'required|numeric',
            'invoice_company_id' => 'nullable|integer',
            'invoice_number' => 'nullable|string',
            'invoicing_date' => 'nullable|date',
            'payment_deadline' => 'nullable|date',
            'is_real' => 'required',
        ]);

        $order = Orders::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $path = null;
        if ($request->file('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('invoices', $fileName, 'public');
        }
        $is_real = $request->is_real === 'true' ? true : false;

        Invoice::create([
            'order_id' => $id,
            'file' => $path ? $path : null,
            'amount' => $request->amount,
            'invoice_company_id' => $request->invoice_company_id,
            'invoice_number' => $request->invoice_number,
            'invoicing_date' => $request->invoicing_date,
            'payment_deadline' => $request->payment_deadline,
            'is_customer' => true,
            'is_real' => $is_real,
        ]);

        if ($order->status != 9) {
            $order->update([
                'status' => 7,
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Invoice successfully uploaded',
        ], 200);
    }

    public function invoiceUpdate(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('accounting'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        Log::info($request->all());

        $request->validate([
            'amount' => 'required|numeric',
            'invoice_company_id' => 'nullable|integer',
            'invoice_number' => 'nullable|string',
            'invoicing_date' => 'nullable|date',
            'payment_deadline' => 'nullable|date',
            'is_real' => 'required',
        ]);

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $path = null;
        if ($request->file) {
            $parsedUrl = parse_url($request->file);
            $paths = $parsedUrl['path'];
            $path = substr($paths, strpos($paths, '/storage/') + strlen('/storage/'));
        }
        if ($request->file('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('invoices', $fileName, 'public');
        }
        $is_real = $request->is_real === 'true' ? true : false;

        $invoice->update([
            'file' => $path ? $path : null,
            'amount' => $request->amount,
            'invoice_company_id' => $request->invoice_company_id,
            'invoice_number' => $request->invoice_number,
            'invoicing_date' => $request->invoicing_date,
            'payment_deadline' => $request->payment_deadline,
            'is_real' => $is_real,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Invoice successfully updated',
        ], 200);
    }

    public function invoiceFile(Request $request, $id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('accounting'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        Log::info($id);

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $filePath = $invoice->file;
        Log::info($filePath);

        $parsedUrl = parse_url($filePath);
        $paths = $parsedUrl['path'];
        $path = substr($paths, strpos($paths, '/storage/') + strlen('/storage/'));

        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice file not found',
            ], 404);
        }
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->download(Storage::disk('public')->path($path), 'invoice.' . $extension);
    }

    public function cancel(Request $request, $id)
    {
        $order = Orders::find($id);
        if (!$order) {

            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ], 404);
        }
        $order::update([
            'status' => 5,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Order successfully cancelled',
        ], 200);
    }

    public function reset(Request $request)
    {
        return $this->helpers->reactivate($request, ['admin', 'sm'], 'Orders');

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
                Excel::import(new OrderImport, $fullPath);
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

    public function accountingExport(Request $request)
    {
        return Excel::download(new AccOrderExport($request->all()), 'acc_orders.csv');
    }

    public function setCollected(Request $request, $id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ], 404);
        }

        $shipment->update([
            'status' => 3,
            'collected_at' => $request->collected_at
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record successfully updated',
        ], 200);
    }

    public function setDelivered(Request $request, $id)
    {
        $shipment = Shipment::find($id);
        $shipment->load('orders');
        $shipmentStatus = $request->customer ? 4 : 5;
        $orderStatus = $request->customer ? 10 : 9;


        if (!$shipment) {

            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ], 404);
        }
        $shipment->update([
            'status' => $shipmentStatus,
            'delivered_at' => $request->delivered_at
        ]);

        foreach ($shipment->orders as $order) {
            $order->status = $orderStatus;
            $order->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record successfully updated',
        ], 200);
    }

    public function setfinalized($id)
    {
        $order = Orders::find($id);
        if (!$order) {

            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ], 404);
        }
        $order->update([
            'status' => 12,
            'finalized_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record successfully updated',
        ], 200);
    }
}

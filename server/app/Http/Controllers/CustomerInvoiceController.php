<?php

namespace App\Http\Controllers;

use App\Models\Customer_invoice;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerInvoiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('accounting'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'file' => 'required|file',
            'amount' => 'required|numeric',
            'invoice_company_id' => 'nullable|integer',
            'invoice_number' => 'nullable|string',
            'invoicing_date' => 'nullable|date',
            'payment_deadline' => 'nullable|date',
        ]);

        $order = Orders::find($id);

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('invoices', $fileName, 'public');
        }

        Customer_invoice::create([
            'order_id' => $id,
            'file' => $path ? $path : null,
            'amount' => $request->amount,
            'invoice_company_id' => $request->invoice_company_id,
            'invoice_number' => $request->invoice_number,
            'invoicing_date' => $request->invoicing_date,
            'payment_deadline' => $request->payment_deadline,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Invoice successfully Uploaded',
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('accounting'))) {
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
        ]);

        $invoice = Customer_invoice::find($id);

        if (! $invoice) {
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
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('invoices', $fileName, 'public');
        }

        $invoice->update([
            'file' => $path ? $path : null,
            'amount' => $request->amount,
            'invoice_company_id' => $request->invoice_company_id,
            'invoice_number' => $request->invoice_number,
            'invoicing_date' => $request->invoicing_date,
            'payment_deadline' => $request->payment_deadline,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Invoice successfully updated',
        ], 200);
    }
}

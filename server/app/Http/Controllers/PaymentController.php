<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Invoice;
use App\Models\Orders;
use App\Models\Payment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    /** Get payment method list */
    public function index(): AnonymousResourceCollection
    {
        return PaymentResource::collection(Payment::all());
    }

    public function store(PaymentRequest $request)
    {
        $items = $request->validated();
        $orderIds = [];
        foreach ($items as $item) {

            $invoice = Invoice::findOrFail($item['invoice_id']);
            $orderIds[] = $invoice->order_id;

            new PaymentResource(Payment::create($item));
        }

        $orderId = $orderIds[0] ?? null;

        if ($orderId) {
            $order = Orders::find($orderId);

            if ($order) {
                $isFullyPaid = $invoice->payments()->sum('amount');
                if ($invoice->amount === $isFullyPaid) {
                    $invoice->update(['is_paid' => true]);
                }
                $status = $invoice->is_customer ? 8 : 6;
                if ($order->status != 9) {
                    $order->update([
                        'status' => $status,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
        ], 201);
    }

    public function show(Payment $payment): PaymentResource
    {
        return new PaymentResource($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $payment,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        $invoice = Invoice::find($payment->invoice_id);

        $isFullyPaid = $invoice->payments()->sum('amount');
        if ($invoice->amount != $isFullyPaid) {
            $invoice->update(['is_paid' => false]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
            'data' => $payment,
        ], 201);
    }
}

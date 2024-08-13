<?php

namespace App\Http\Filters;

use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Models\Offers;
use App\Models\Orders;
use DateTime;
use Log;

class OrderFilter extends Filter
{
    public function index(OrderRequest $request)
    {
        log::info($request->all());
        $query = Orders::query();

        if ($request->filled('order_id')) {
            $query->where('id', $request->input('order_id'));
        }

        if ($request->filled('offer_id')) {
            $query->where('offer_id', $request->input('offer_id'));
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

        if ($request->filled('customer')) {
            $query->where('customer_id', $request->input('customer'));
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->input('supplier'));
        }

        if ($request->filled('model')) {
            $query->where('reference_number', $request->input('model'));
        }

        // if ($request->filled('status')) {
        //     $query->where('status', $request->input('status'));
        // }

        if ($request->filled('status')) {
            $statuses = $request->input('status');
            if (is_array($statuses)) {
                $query->whereIn('status', $statuses);
            } else {
                $query->where('status', $statuses);
            }
        }

        $orders = $query->with('brand:id,name', 'customer.country', 'supplier.brands', 'supplier.country', 'offer', 'expenses')->get();

        foreach ($orders as $order) {
            $this->addInvoices($order);
        }

        $data = [];

        foreach ($orders as $order) {
            if ($this->datesRangeCheck($request, $order) && $this->roleCheck($order)) {
                $data[] = $order;
            }
        }

        return response()->json($data);
    }

    public function datesRangeCheck(OrderRequest $request, Orders $order): bool
    {

        if ($request->filled('from') && $request->filled('to')) {
            if ($order->created_at) {
                if (!$this->DateInBetween($request->input('from'), $request->input('to'), $order->created_at)) {
                    return false;
                }
            }
        }

        if ($request->filled('deadline_from') && $request->filled('deadline_to')) {
            if ($order->deadline) {
                if (!$this->DateInBetween($request->input('deadline_from'), $request->input('deadline_to'), $order->deadline)) {
                    return false;
                }
            }
        }

        if ($request->filled('confirm_from') && $request->filled('confirm_to')) {
            if ($order->confirmed_at) {
                if (!$this->DateInBetween($request->input('confirm_from'), $request->input('confirm_to'), $order->confirmed_at)) {
                    return false;
                }
            }
        }

        if ($request->filled('date_invoice_from') && $request->filled('date_invoice_to')) {
            $invoice = Invoice::where(['order_id' => $order->id])->first();
            if (!$invoice) {
                return false;
            }
            if (!$this->DateInBetween($request->input('date_invoice_from'), $request->input('date_invoice_to'), $invoice->created_at)) {
                return false;
            }
        }

        if ($request->filled('shipment_date_from') && $request->filled('shipment_date_to')) {

            if ($order->shipment_date) {
                if (!$this->DateInBetween($request->input('shipment_date_from'), $request->input('shipment_date_to'), $order->shipment_date)) {
                    return false;
                }
            }
        }

        if ($request->filled('net_price_from') && $request->filled('net_price_to')) {
            if (!$order->offer_id) {
                return false;
            }
            if ($order->offer_id) {
                $offer = Offers::find($order->offer_id);
                if ($request->filled('net_price_from') && $request->filled('net_price_to')) {
                    if (!(($offer->net_price >= $request->input('net_price_from')) && ($offer->net_price <= $request->input('net_price_to')))) {
                        return false;
                    }
                }
            }
        }

        if ($request->filled('user')) {
            if ($order->customer_id) {
                if (!(Customer::find($order->customer_id)->user_id == $request->input('user'))) {
                    return false;
                }
            }
        }

        return true;
        
    }

    public function DateInBetween($d1, $d2, $d): bool
    {
        $dTimestamp = intval(strtotime((new Datetime($d))->format('Y-m-d H:i:s')));
        $d1Timestamp = intval(strtotime((new Datetime($d1))->format('Y-m-d H:i:s')));
        $d2Timestamp = intval(strtotime((new Datetime($d2))->format('Y-m-d H:i:s')));

        if (($dTimestamp >= $d1Timestamp) && ($dTimestamp <= $d2Timestamp)) {
            return true;
        }

        return false;
    }

    public function roleCheck(Orders $order): bool
    {
        $user = Auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('pm')) {
            return true;
        }

        if (($user->hasrole('accounting') || $user->hasrole('logistic'))) {
            if (!in_array($order->status, ['New', 'Proposed', 'SM Confirmed', 'Finalized', 'Cancelled'])) {
                return true;
            }
        }

        if ($user->hasRole('sm')) {
            if ((Customer::find($order->customer_id)->user_id) == $user->id) {
                return true;
            }
        }

        return false;
    }

    public function addInvoices(Orders $order)
    {
        $user = Auth()->user();

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
        if ($supplierInvoice && $customerInvoice) {
            if ($supplierInvoice->is_paid && $customerInvoice->is_paid) {
                $order->status = 9;
                $order->save();
            }
        }
        $order->supplier_invoice = $supplierInvoice;
        $order->customer_invoice = $customerInvoice;


        if ($proposal) {
            $order->proposal = $proposal;
            $order->profit = $order->proposal->sell_price - $order->offer->net_price;
        }

    }
}

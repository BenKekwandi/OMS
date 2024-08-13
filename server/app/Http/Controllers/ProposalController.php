<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Offers;
use App\Models\Orders;
use App\Models\Proposal;
use App\Models\Supplier;
use App\Rules\UniqueProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProposalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $proposals = Proposal::with('offer.supplier', 'offer.brand')->with('order.customer')->get();

        if ($user->hasrole('pm')) {
            $props = [];
            $proposals = Proposal::with('offer.supplier', 'offer.brand')->with('order.customer')->where(['status' => 1])->get();
            foreach ($proposals as $proposal) {
                $offer = Offers::find($proposal->offer_id);
                $supplier = Supplier::find($offer->supplier_id);
                if ($supplier->user_id == $user->id) {
                    $props[] = $proposal;
                }
            }

            $proposals = $props;
        }

        if ($user->hasrole('sm')) {
            $props = [];
            $proposals = Proposal::with('offer.supplier', 'offer.brand')->with('order.customer')->where(['status' => 0])->get();
            foreach ($proposals as $proposal) {
                $order = Orders::find($proposal->order_id);
                $customer = Customer::find($order->customer_id);
                if ($customer->user_id == $user->id) {
                    $props[] = $proposal;
                }
            }

            $proposals = $props;
        }

        return response()->json([
            'status' => 'success',
            'data' => $proposals,
        ]);
    }

    public function smConfirmation()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $proposals = Proposal::with('offer.supplier', 'offer.brand')->with('order.customer')->where(['status' => 0])->get();

        if ($user->hasrole('sm')) {
            $props = [];

            foreach ($proposals as $proposal) {
                $order = Orders::find($proposal->order_id);
                $customer = Customer::find($order->customer_id);
                if ($customer->user_id == $user->id) {
                    $props[] = $proposal;
                }
            }

            $proposals = $props;
        }

        return response()->json([
            'status' => 'success',
            'data' => $proposals,
        ]);
    }

    public function pmConfirmation()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $proposals = Proposal::with('offer.supplier', 'offer.brand')->with('order.customer')->where(['status' => 1])->get();

        if ($user->hasrole('pm')) {
            $props = [];

            foreach ($proposals as $proposal) {
                $offer = Offers::find($proposal->offer_id);
                $supplier = Supplier::find($offer->supplier_id);
                if ($supplier->user_id == $user->id) {
                    $props[] = $proposal;
                }
            }

            $proposals = $props;
        }

        return response()->json([
            'status' => 'success',
            'data' => $proposals,
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
            'sell_price' => 'required|numeric',
            'profit' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
            'delivery_days' => 'integer',
        ]);

        $proposal = Proposal::create([
            'order_id' => $request->order_id,
            'offer_id' => $request->offer_id,
            'sell_price' => $request->sell_price,
            'notes' => $request->notes,
            'delivery_days' => $request->delivery_days,
            'profit' => $request->profit,
            'applied_at' => now(),
        ]);

        Offers::where('id', $request->offer_id)->update(['status' => 2]);
        Orders::where('id', $request->order_id)->update(['status' => 2]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record created successfully.',
            'data' => $proposal,
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

        $request->validate([
            'sell_price' => 'nullable|numeric',
            'net_price' => 'nullable|numeric',
            'notes' => 'nullable|string|max:255',
            'Deadline' => 'nullable|date',
            'name_for_warranty' => 'nullable|string|max:255',
            'delivery_days' => 'integer',
            'confirmed_at' => 'nullable|date',
        ]);

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }
        $order = Orders::find($proposal->order_id);
        $offer = Offers::find($proposal->offer_id);


        $offer->update([
            'net_price' => $request->net_price,
        ]);

        $order->update([
            'name_for_warranty' => $request->name_for_warranty,
            'deadline' => $request->deadline,
        ]);

        $proposal->update([
            'sell_price' => $request->sell_price,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record updated successfully.',
            'data' => $proposal,
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

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $proposal,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.',
            ], 404);
        }
        $proposal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully.',
        ]);
    }

    public function confirm(Request $request, $id)
    {

        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'supplier_id' => 'required|integer',
        ]);

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $order = Orders::find($proposal->order_id);
        $offer = Offers::find($proposal->offer_id);

        $offer->update([
            'status' => 3,
        ]);

        $order->update([
            'offer_id' => $offer->id,
            'proposal_id' => $proposal->id,
            'supplier_id' => $offer->supplier_id,
            'confirmed_at' => now()->format('Y-m-d H:i:s'),
            'status' => 3,
        ]);

        $proposal->update([
            'status' => 1,
            'confirmed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record Confirmed Successfully',
            'data' => $proposal,
        ], 201);
    }

    public function cancel(Request $request, $id)
    {

        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'notes' => 'required|string',
        ]);

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $order = Orders::find($proposal->order_id);
        $offer = Offers::find($proposal->offer_id);

        $offer->update([
            'status' => 1,
        ]);

        $order->update([
            'status' => 12,
        ]);

        $proposal->update([
            'status' => 3,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record Cancelled Successfully',
        ], 201);
    }

    public function pmconfirm(Request $request, $id)
    {

        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        log::info($request->all());
        log::info($id);

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $order = Orders::with('supplier', 'customer')->find($proposal->order_id);

        if ($order->supplier->is_credit || $order->customer->is_credit) {
            $order->update([
                'status' => 9,
            ]);
        } else {
            $order->update([
                'status' => 4,
            ]);
        }

        $proposal->update([
            'status' => 2,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Record Confirmed Successfully',
            'data' => $proposal,
        ], 201);
    }

    public function pmcancel(Request $request, $id)
    {

        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not Authorized',
            ], 401);
        }

        $request->validate([
            'notes' => 'required|string',
        ]);

        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record Not Found',
            ], 404);
        }

        $order = Orders::find($proposal->order_id);
        $offer = Offers::find($proposal->offer_id);

        $offer->update([
            'status' => 1,
        ]);

        $order->update([
            'status' => 1,
        ]);

        $proposal->update([
            'notes' => $request->notes,
            'status' => 3,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Record Cancelled Successfully',
        ], 201);
    }
}

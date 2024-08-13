<?php

namespace App\Http\Filters;

use App\Http\Requests\OfferRequest;
use App\Models\offers;
use App\Models\orders;
use App\Models\Supplier;
use DateTime;
use Log;
use Illuminate\Support\Facades\Auth;

class OfferFilter extends Filter
{
    public function index(OfferRequest $request)
    {

        log::info($request->all());
        $user = Auth::user();
        $supplierIds = $user->suppliers->pluck('id');
        $query = Offers::query();

        if ($request->filled('offer_id')) {
            $query->where('id', $request->input('offer_id'));
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

         if ($request->filled('serial_number')) {
            $query->where('serial_number', $request->input('serial_number'));
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->input('supplier'));
        }

        if ($request->filled('model')) {
            $query->where('reference_number', $request->input('model'));
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->input('availability'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('with_image') && $request->input('with_image')) {
            $query->where('image', !empty($item['image']));
        }

        if ($request->filled('my_offers') && $request->input('my_offers')) {
            $query->whereIn('supplier_id', $supplierIds);
        }

        $offers = $query->with('brand:id,name')->with(['supplier:id,name,user_id'])->get();

        $data = [];

        foreach ($offers as $offer) {
            if ($this->datesRangeCheck($request, $offer)) {
                $data[] = $offer;
            }
        }

        foreach ($data as $item) {
            $item['matches'] = Orders::where([
                'brand_id' => $item['brand_id'],
                'reference_number' => $item['reference_number'],
            ])->whereIn('status', [1, 2])->count();

        }

        return response()->json($data);
    }

    public function datesRangeCheck(OfferRequest $request, Offers $offer): bool
    {

        if ($request->filled('created_from') && $request->filled('created_to')) {
            if (!$this->DateInBetween($request->input('created_from'), $request->input('created_to'), $offer->created_at)) {
                return false;
            }
        }

        if ($request->filled('order_days_from') && $request->filled('order_days_to')) {
            if (!(($offer->order_days >= $request->input('order_days_from')) && ($offer->order_days <= $request->input('order_days_to')))) {
                return false;
            }
        }

        if ($request->filled('user')) {
            if ($offer->supplier_id) {
                if (!(Supplier::find($offer->supplier_id)->user_id == $request->input('user'))) {
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

    // public function roleCheck(Offers $offer): bool
    // {
    //     $user = Auth()->user();

    //     if ($user->hasRole('admin') || $user->hasRole('sm')) {
    //         return true;
    //     }

    //     if ($user->hasRole('pm')) {
    //         if ((Supplier::find($offer->supplier_id)->user_id) == $user->id) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }
}

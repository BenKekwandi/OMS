<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\ShipmentTypeEnum;
use Illuminate\Validation\Rules\Enum;



class ShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('logistic'))) {

            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shipment_account_id' => ['required', 'exists:shipment_accounts,id'],
            'shipping_type' => ['required', new Enum(ShipmentTypeEnum::class)],
            'automatic_shipping' => ['required', 'boolean'],
            'ship_to_title' => ['string'],
            'ship_from_title' => ['string'],
            'ship_to_id' => ['required', 'exists:office_addresses,id'],
            'ship_from_id' => ['required', 'exists:office_addresses,id'],
            'box_weight' => ['nullable', 'numeric'],
            'box_width' => ['nullable', 'numeric'],
            'box_height' => ['nullable', 'numeric'],
            'box_depth' => ['nullable', 'numeric'],
            'deadline' => ['required'],
            'pick_up_time' => ['required'],
            'collected_at' => ['nullable'],
            'delivered_at' => ['nullable'],
        ];
    }
}

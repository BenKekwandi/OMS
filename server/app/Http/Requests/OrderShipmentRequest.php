<?php

namespace App\Http\Requests;

use App\Models\OrderShipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class OrderShipmentRequest extends FormRequest
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
            '*.order_id' => ['required', 'exists:orders,id'],
            '*.shipment_id' => ['required', 'exists:shipments,id'],
        ];
    }
}

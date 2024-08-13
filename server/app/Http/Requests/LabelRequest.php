<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Log;

class LabelRequest extends FormRequest
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
        Log::info('Request Data:', $this->all());

        return [
            'shipment_id' => ['required', 'exists:shipments,id'],
            'file' => ['nullable'],
            'amount' => ['nullable'],
            'tracking_number' => ['nullable', 'string'],
            'postment_id' => ['nullable', 'string'],
            'expected_collection_at' => ['nullable'],
            'expected_delivery_at' => ['nullable'],
            'response' => ['string'],
        ];
    }
}

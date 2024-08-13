<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class OfferRequest extends FormRequest
{
    public function authorize()
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm') || $user->hasrole('accounting'))) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'created_from' => ['nullable', 'date'],
            'created_to' => ['nullable', 'date'],
            'order_days_from' => 'nullable|numeric',
            'order_days_to' => 'nullable|numeric',
            'reference_number' => 'nullable|string',
            'supplier' => 'nullable|integer',
            'brand' => 'nullable|integer',
            'user' => 'nullable|integer',
            'status' => 'nullable|integer',
            'availability' => 'nullable|integer',
            'with_image' => 'nullable|boolean',
            'my_offers' => 'nullable|boolean',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {

            if (($this->filled('created_from') && !$this->filled('created_to')) || ($this->filled('created_to') && !$this->filled('created_from'))) {
                $validator->errors()->add('created_from', 'Both start and end date ranges are required.');
                $validator->errors()->add('created_to', 'Both start and end date ranges are required.');
            }

            if (($this->filled('created_from') && $this->filled('created_to')) && (Carbon::parse($this->created_from)->gt(Carbon::parse($this->created_to)))) {
                $validator->errors()->add('created_from', 'The start date must be before the end date.');
                $validator->errors()->add('created_to', 'The end date must be after the start date.');
            }

        });
    }
}

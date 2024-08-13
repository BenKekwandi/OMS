<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OfficeAddressRequest extends FormRequest
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
            'contact_Name' => ['required', 'string'],
            'company' => ['string'],
            'street_1' => ['required', 'string'],
            'street_2' => ['nullable', 'string'],
            'street_3' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'state' => ['nullable','string'],
            'country' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'post_code' => ['required','string'],
            'tax' => ['nullable','string'],
            'phone' => ['required','string'],
        ];
    }
}

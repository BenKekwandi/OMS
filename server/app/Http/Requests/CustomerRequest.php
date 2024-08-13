<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('sm')))
            return false;

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
            'name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Customer::class],
            'phone' => 'nullable|string',
            'country_id' => 'required|integer',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'is_credit' => 'boolean|nullable',
        ];
    }
}

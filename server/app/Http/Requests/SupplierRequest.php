<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm')))
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
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Supplier::class],
            'phone' => 'nullable|string',
            'country_id' => 'required|integer',
            'address' => 'nullable|string',
            'primary_name' => 'nullable|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'invoice_delivery_rules' => 'nullable|string',
            'tax' => 'nullable|string',
            'is_credit' => 'nullable|boolean',
        ];
    }
}

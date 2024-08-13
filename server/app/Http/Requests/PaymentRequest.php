<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (! ($user->hasrole('admin') || $user->hasrole('accounting'))) {

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
            '*.amount' => ['required', 'numeric'],
            '*.paid_at' => ['required', 'date'],
            '*.invoice_id' => ['required', 'exists:invoices,id'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Rules\UniqueExpense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'paid_at' => ['required', 'date'],
            'expenses_type_id' => ['exists:expenses_types,id'],
            'order_id' => ['exists:orders,id'],
        ];
    }
}

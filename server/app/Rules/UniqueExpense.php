<?php

namespace App\Rules;

use App\Models\Expenses;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueExpense implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Expenses::where('order_id', $this->order_id)
            ->where('expenses_type_id', $value)
            ->exists();

        if ($exists) {
            $fail("An expense with order ID $this->order_id and expense ID $value already exists.");
        }
    }
}

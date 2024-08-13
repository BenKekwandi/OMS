<?php

namespace App\Rules;

use App\Models\Proposal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Log;

class UniqueProposal implements ValidationRule
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
        $exists = Proposal::where('order_id', $this->order_id)
            ->where('offer_id', $value)
            ->exists();

        if ($exists) {
            $fail("A proposal with order ID $this->order_id and offer ID $value already exists.");
        }
    }
}

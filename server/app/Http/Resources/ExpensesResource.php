<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'paid_at' => $this->paid_at,
            'invoice_id' => $this->invoice_id,
            'expenses_type_id' => $this->expenses_type_id,
            'order_id' => $this->order_id,
        ];
    }
}

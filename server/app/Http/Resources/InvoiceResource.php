<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_company' => new InvoiceCompanyResource($this->invoice_company),
            'invoice_number' => $this->invoice_number,
            'invoicing_date' => $this->invoicing_date,
            'payment_deadline' => $this->payment_deadline,
            'is_real' => $this->is_real,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceCompanyResource extends JsonResource
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
            'company' => $this->company,
            'country' => $this->country,
            'location' => $this->location,
            'phone' => $this->phone,
            'contact_name' => $this->contact_name,
        ];
    }
}

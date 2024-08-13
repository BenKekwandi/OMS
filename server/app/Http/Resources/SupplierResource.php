<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'primary_name' => $this->primary_name,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'invoice_delivery_rules' => $this->invoice_delivery_rules,
            'tax' => $this->tax,
            'is_credit' => $this->is_credit,
            'country' => new CountryResource($this->country),
            'brands' => BrandResource::collection($this->brands),
            'pm' => new UserResource($this->pm),

        ];
    }
}

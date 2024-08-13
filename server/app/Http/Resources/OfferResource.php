<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'brand' => new BrandResource($this->brand),
            'supplier' => new SupplierResource($this->supplier),
            'reference_number' => $this->reference_number,
            'discount' => $this->discount,
            'net_price' => $this->net_price,
            'rrp_price' => $this->rrp_price,
            'rrp_explanation' => $this->rrp_explanation,
            'image' => $this->image,
            'other_features' => $this->other_features,
            'warehouse' => new WarehouseResource($this->warehouse),
            'order_days' => $this->order_days,
            'serial_number' => $this->serial_number,
            'availability' => $this->availability,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}

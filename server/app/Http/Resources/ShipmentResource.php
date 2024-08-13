<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
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
            'shipment_account' => new ShipmentAccountResource($this->shipment_account),
            'shipping_type' => $this->shipping_type,
            'automatic_shipping' => $this->automatic_shipping,
            'status' => $this->status,
            'ship_to_title' => $this->ship_to_title,
            'ship_from_title' => $this->ship_from_title,
            'ship_to_id' => new OfficeAddressResource($this->shipTo),
            'ship_from' => new OfficeAddressResource($this->shipFrom),
            'box_weight' => $this->box_weight,
            'box_width' => $this->box_width,
            'box_height' => $this->box_height,
            'box_depth' => $this->box_depth,
            'pick_up_time' => $this->pick_up_time,
            'deadline' => $this->deadline,
            'collected_at' => $this->collected_at,
            'delivered_at' => $this->delivered_at,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'label' => new LabelResource($this->label),

        ];
    }
}

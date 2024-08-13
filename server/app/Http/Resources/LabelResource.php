<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabelResource extends JsonResource
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
            'shipment' => new ShipmentResource($this->whenLoaded('shipment')),
            'kind' => $this->kind,
            'file' => $this->file,
            'amount' => $this->amount,
            'tracking_number' => $this->tracking_number,
            'postmen_id' => $this->postmen_id,
            'expected_collection_at' => $this->expected_collection_at,
            'expected_delivery_at' => $this->expected_delivery_at,
            'response' => $this->response,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'label_invoice' => LabelInvoiceResource::collection($this->label_invoice),
        ];
    }
}

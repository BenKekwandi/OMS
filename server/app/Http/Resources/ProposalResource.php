<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order' => new OfferResource($this->whenLoaded('order')),
            'offer' => new OfferResource($this->whenLoaded('offer')),
            'sell_price' => $this->sell_price,
            'notes' => $this->notes,
            'delivery_days' => $this->sell_price,
            'profit' => $this->profit,
            'status' => $this->status,
            'applied_at' => $this->applied_at,
            'confirmed_at' => $this->confirmed_at,
        ];
    }
}

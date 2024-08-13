<?php

namespace App\Http\Resources;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'offer' => new OfferResource($this->whenLoaded('offer')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'expenses'=> ExpensesResource::collection($this->whenLoaded('expenses')),
            'invoice'=>InvoiceResource::collection($this->whenLoaded('invoice')),
            'proposal' => ProposalResource::collection($this->whenLoaded('proposal')),
            'image' => $this->image,
            'other_features' => $this->other_features,
            'reference_number' => $this->reference_number,
            'name_for_warranty' => $this->name_for_warranty,
            'matches' => $this->matches,
            'is_read' => $this->is_read,
            'confirmed_at' => $this->confirmed_at,
            'expected_arrival' => $this->expected_arrival,
            'actual_arrival' => $this->actual_arrival,
            'shipment_date' => $this->shipment_date,
            'expected_delivery_at' => $this->expected_delivery_at,
            'finalized_at' => $this->finalized_at,
            'deadline' => $this->deadline,
            'status' => $this->status,
       ];
    }
}

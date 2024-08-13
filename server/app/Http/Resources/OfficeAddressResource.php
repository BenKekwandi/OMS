<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficeAddressResource extends JsonResource
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
            'contact_name' => $this->contact_name,
            'company' => $this->company,
            'street_1' => $this->street_1,
            'street_2' => $this->street_2,
            'street_3' => $this->street_3,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'email' => $this->email,
            'post_code' => $this->post_code,
            'tax' => $this->tax,
            'phone' => $this->phone,
            'fax' => $this->fax,
        ];
    }
}

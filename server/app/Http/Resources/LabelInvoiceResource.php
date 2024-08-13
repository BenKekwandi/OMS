<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabelInvoiceResource extends JsonResource
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
            'label' => new LabelResource($this->whenLoaded('label')),
            'kind' => $this->kind,
            'serial_number' => $this->serial_number,
            'copies' => $this->copies,
            'date' => $this->date,
        ];
    }
}

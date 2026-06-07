<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'invoice_number' => $this->invoice_number,
            'status'         => $this->status,
            'issue_date'     => $this->issue_date,
            'due_date'       => $this->due_date,
            'currency'       => $this->currency,
            'total'          => $this->total,
            'notes'          => $this->notes,
            'client'         => new ClientResource($this->whenLoaded('client')),
            'items'          => InvoiceItemResource::collection($this->whenLoaded('items')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}

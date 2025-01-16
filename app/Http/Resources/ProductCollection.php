<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'sku' => $this->sku,
            'status' => $this->status,
            'price' => $this->price,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'product_quantity' => $this->product_quantity,
            'variations' => ProductVariationCollection::collection($this->whenLoaded('variations')),
        ];
    }
}

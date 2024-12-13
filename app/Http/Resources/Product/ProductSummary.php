<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSummary extends JsonResource
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
            'slug' => $this->slug,
            'image_url' => $this->image_url,
            'name' => $this->name,
            'stock_qty' => $this->stock_qty,
            'price' => $this->price,
            'variations' => VariationResource::collection($this->whenLoaded('variations'))
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\VariationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_variation_id' => $this->product_variation_id,
            'product_id' => $this->product_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_amount' => $this->total_amount,
            'variation' => new VariationResource($this->whenLoaded('variation')),
            'product' => new ProductResource(resource: $this->whenLoaded('product')),
            'detail_item'=>$this->detail_item,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\VariationResource;
use App\Http\Resources\Product\VariationResourceForCart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartCollection extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_variation_id' => $this->product_variation_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'product' => new ProductResource($this->product),
            'product_variation' => new VariationResourceForCart($this->product_variation),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

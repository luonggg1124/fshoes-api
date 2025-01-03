<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BestSellingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product_id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'image_url' => $this->product->image_url,
            'rating' => $this->product->averageRating(),
            'stock_qty' => $this->product->stock_qty,
            'qty_sold' => $this->product->qty_sold,
            'description' => $this->product->description,
            'short_description' => $this->product->short_description,
            'total_sold_quantity' => $this->total_sold_quantity
        ];
    }
}

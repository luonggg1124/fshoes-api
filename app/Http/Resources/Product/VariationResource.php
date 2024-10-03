<?php

namespace App\Http\Resources\Product;


use App\Http\Resources\ImageResource;
use App\Http\Resources\ProductResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariationResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'images' => ImageResource::collection($this->images),
            'values' => $this->whenLoaded('values'),
            'price' => $this->price,
            'sku' => $this->sku,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'status' => $this->status,
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
            'created_at' => (new Carbon($this->created_at))->format('H:m d-m-Y')

        ];
    }
}

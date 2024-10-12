<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\VariationResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'image_url' => $this->image_url,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => number_format($this->price),
            'description' => $this->description,
            'short_description' => $this->short_description,
            'status' => $this->status,
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
            'created_at' => (new Carbon($this->created_at))->format('H:m d-m-Y'),
            'updated_at' => (new Carbon($this->updated_at))->format('H:m d-m-Y'),
            'deleted_at' => (new Carbon($this->deleted_at))->format('H:m d-m-Y')
        ];
    }
}

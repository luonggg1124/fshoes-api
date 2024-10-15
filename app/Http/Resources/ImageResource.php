<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\VariationResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
            'url' => $this->url,
            'public_id' => $this->public_id,
            'alt_text' => $this->alt_text,
            'created_at' => (new Carbon($this->created_at))->format('d-m-Y H:i:s')
        ];
    }
}

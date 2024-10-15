<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => (new Carbon($this->created_at))->format('d-m-Y H:i:s'),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'parents' => CategoryResource::collection($this->whenLoaded('parents')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}

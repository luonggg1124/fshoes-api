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
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => $this->image_url,
            'created_at' => (new Carbon($this->created_at))->format('H:m d-m-Y'),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}

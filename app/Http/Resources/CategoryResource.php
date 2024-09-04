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
            'parent_id' => $this->parent_id,
            'slug' => $this->slug,
            'image_url' => $this->image_url,
            'public_id' => $this->public_id,
            'created_at' => (new Carbon($this->created_at))->format("H:m d-m-Y"),
        ];
    }
}

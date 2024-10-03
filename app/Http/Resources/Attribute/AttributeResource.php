<?php

namespace App\Http\Resources\Attribute;

use App\Http\Resources\Attribute\Value\ValueResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'id'=> $this->id,
            'values' => ValueResource::collection($this->whenLoaded('values')),
            'name' => $this->name,
            'created_at' => (new Carbon($this->created_at))->format('H:m d-m-Y'),
            'updated_at' => (new Carbon($this->updated_at))->format('H:m d-m-Y'),
            'deleted_at' => (new Carbon($this->deleted_at))->format('H:m d-m-Y')
        ];
    }
}

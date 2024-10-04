<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user"=>new UserResource($this->whenLoaded('user')),
            "product"=>new ProductResource($this->whenLoaded('product')),
            'created_at' => (new Carbon($this->created_at))->format('H:m d-m-Y'),
            'updated_at' => (new Carbon($this->updated_at))->format('H:m d-m-Y'),
        ];
    }
}

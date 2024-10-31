<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\ProductResource;
use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public static $wrap = false; // Tắt wrapping mặc định của resource

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
            'user' => new UserResource($this->whenLoaded('user')), 
            'title' => $this->title,
            'text' => $this->text,
            'rating' => $this->rating,
            'likes_count' => $this->likes()->count(), 
            'created_at' => $this->created_at, 
            'updated_at' => $this->updated_at,
        ];
    }
}

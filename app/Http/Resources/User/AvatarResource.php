<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvatarResource extends JsonResource
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
            'user' => new UserResource($this->user()),
            'avatar_url' => $this->avatar_url,
            'cloudinary_public_id' => $this->cloudinary_public_id,
            'created_at' => (new \Carbon\Carbon($this->created_at))->format('d-m-Y H:i:s')
        ];
    }
}

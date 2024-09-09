<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'nickname' => $this->nickname,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'email_verified_at' => $this->email_verified_at ? (new Carbon($this->email_verified_at))->format('H:m d-m-Y') : null,
            'google_id' => $this->google_id,
            'is_admin' => $this->is_admin,
            'status' => $this->status,
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
            'interestingCategories' => CategoryResource::collection($this->whenLoaded('interestingCategories')),
            'addresses' => UserAddressResource::collection($this->whenLoaded('addresses')),
            'avatar' => new AvatarResource($this->avatar()),
            'allAvatars' => AvatarResource::collection($this->whenLoaded('allAvatars'))
        ];
    }
}

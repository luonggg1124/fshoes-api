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
            'email_verified_at' => $this->email_verified_at ? (new Carbon($this->email_verified_at))->format('d-m-Y H:i:s') : null,
            'google_id' => $this->google_id,
            'is_admin' => $this->is_admin,
            'status' => $this->status,
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
        ];
    }
}

<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'given_name' => $this->given_name,
            'family_name' => $this->family_name,
            'address_active_id' => $this->address_active_id ? new UserAddressResource($this->addressActive()) : null,
            'birth_date' => $this->birth_date ? (new Carbon($this->birth_date))->format('d-m-Y') : null,
            'created_at' => (new Carbon($this->created_at))->format('H:m d-m-Y'),
            'updated_at' => (new Carbon($this->updated_at))->format('H:m d-m-Y'),
        ];
    }
}

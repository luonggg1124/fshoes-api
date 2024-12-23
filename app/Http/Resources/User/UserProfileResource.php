<?php

namespace App\Http\Resources\User;

use App\Http\Traits\ResourceSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    use ResourceSummary;

    public static $wrap = false;
    private string $model = 'user_profile';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'given_name' => $this->given_name,
            'family_name' => $this->family_name,
            'detail_address' => $this->detail_address,
            'birth_date' => $this->birth_date,

        ];
        if ($this->includeTimes($this->model))
        {
            $resource['created_at']  = $this->created_at;
            $resource['updated_at']  = $this->updated_at;
            $resource['deleted_at']  = $this->updated_at;
        }


        return $resource;
    }
}

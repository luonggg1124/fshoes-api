<?php

namespace App\Http\Resources\User;

use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
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
            'address_active_id' => $this->address_active_id ? new UserAddressResource($this->addressActive()) : null,
            'birth_date' => $this->birth_date ? (new Carbon($this->birth_date))->format('d-m-Y') : null,

        ];
        if ($this->includeTimes($this->model))
        {
            $resource['created_at'] = (new Carbon($this->created_at))->format('d-m-Y H:i:s');
            $resource['updated_at'] = (new Carbon($this->updated_at))->format('d-m-Y H:i:s');
            $resource['deleted_at'] = (new Carbon($this->updated_at))->format('d-m-Y H:i:s');
        }


        return $resource;
    }
}

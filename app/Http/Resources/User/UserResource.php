<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ProductResource;
use App\Http\Traits\ResourceSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ResourceSummary;
    private $model = 'user';
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'avatar_url' => $this->avatar_url,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'google_id' => $this->google_id,
            'status' => $this->status,
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
            'favoriteProducts' => ProductResource::collection($this->whenLoaded('favoriteProducts')),
        ];
        if($this->shouldSummaryRelation($this->model))
            $resource = [
                'id' => $this->id,
                'nickname' => $this->nickname,
                'avatar_url' => $this->avatar_url,
                'name' => $this->name,
                'email' => $this->email,
            ];
        if($this->includeTimes($this->model)){
            $resource['created_at'] = $this->created_at;
            $resource['updated_at'] = $this->updated_at;
            $resource['deleted_at'] = $this->updated_at;
        }
        return $resource;
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    use ResourceSummary;
    public static $wrap = false;
    private string $model = 'category';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'parents' => CategoryResource::collection($this->whenLoaded('parents')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
        if($this->shouldSummaryRelation($this->model))
            $resource = [
                'id' => $this->id,
                'name' => $this->name,
                'products' => ProductResource::collection($this->whenLoaded('products')),
                'parents' => CategoryResource::collection($this->whenLoaded('parents')),
                'children' => CategoryResource::collection($this->whenLoaded('children')),
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

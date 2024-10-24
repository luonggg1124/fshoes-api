<?php

namespace App\Http\Resources\Attribute;

use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    use ResourceSummary;
    public static $wrap = false;
    private string $model = 'attribute';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id'=> $this->id,
            'values' => ValueResource::collection($this->whenLoaded('values')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'name' => $this->name,
        ];
        if($this->shouldSummaryRelation($this->model)){
            $resource = [
                'id'=> $this->id,
                'values' => ValueResource::collection($this->whenLoaded('values')),
                'product' => new ProductResource($this->whenLoaded('product')),
            ];
        }
        if(\request()->query('times')){
            $resource['created_at']  = $this->created_at;
            $resource['updated_at']  = $this->updated_at;
            $resource['deleted_at']  = $this->updated_at;
        }
        return $resource;
    }
}

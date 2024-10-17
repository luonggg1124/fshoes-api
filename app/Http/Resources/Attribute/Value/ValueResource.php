<?php

namespace App\Http\Resources\Attribute\Value;

use App\Http\Resources\Attribute\AttributeResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValueResource extends JsonResource
{
    use ResourceSummary;
    public static $wrap = false;
    private string $model = 'attribute_value';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id'=> $this->id,
            'attribute' => new AttributeResource($this->whenLoaded('attribute')),
            'variations' => ValueResource::collection($this->whenLoaded('variations')),
            'value' => $this->value,

        ];
        if($this->shouldSummaryRelation($this->model)) $resource = [
            'id'=> $this->id,
            'attribute' => new AttributeResource($this->whenLoaded('attribute')),
            'variations' => ValueResource::collection($this->whenLoaded('variations')),
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

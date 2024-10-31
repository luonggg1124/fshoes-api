<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\VariationResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    use ResourceSummary;
    public static $wrap = false;
    private string $model = 'image';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
            'url' => $this->url,
            'public_id' => $this->public_id,
            'alt_text' => $this->alt_text,
        ];
        if($this->shouldSummaryRelation($this->model)) $resource = [
            'id' => $this->id,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
            'url' => $this->url,
            'public_id' => $this->public_id,
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

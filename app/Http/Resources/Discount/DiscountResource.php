<?php

namespace App\Http\Resources\Discount;

use App\Http\Resources\Product\VariationResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    use ResourceSummary;

    public static $wrap = false;
    private string $model = 'discount';

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
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
        ];
        if ($this->includeTimes($this->model)) {
            $resource['created_at'] = $this->created_at;
            $resource['updated_at'] = $this->updated_at;
            $resource['deleted_at'] = $this->deleted_at;
        }
        return $resource;
    }
}

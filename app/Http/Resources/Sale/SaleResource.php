<?php

namespace App\Http\Resources\Sale;

use App\Http\Resources\Product\VariationResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ResourceSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    use ResourceSummary;

    public static $wrap = false;
    private string $model = 'sale';

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
            'products' => $this->whenLoaded('products',function($products){
                return $products->map(function ($product){
                    return [
                        ...$product->toArray(),
                        'qty_sale' => $product->pivot->quantity
                    ];
                });
            }),
            'variations' => $this->whenLoaded('variations',function($variations){
                return $variations->map(function ($variation){
                    return [
                        ...$variation->toArray(),
                        'qty_sale' => $variation->pivot->quantity
                    ];
                });
            }),
           
        ];
        if ($this->includeTimes($this->model)) {
            $resource['created_at'] = $this->created_at;
            $resource['updated_at'] = $this->updated_at;
            $resource['deleted_at'] = $this->deleted_at;
        }
        return $resource;
    }
}

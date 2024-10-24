<?php

namespace App\Http\Resources;

use App\Http\Resources\Product\VariationResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    use ResourceSummary;

    public static $wrap = false;
    private string $model = 'product';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'sale_price' => $this->sale_price ,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'status' => $this->status,
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
        ];
        if ($this->shouldSummaryRelation($this->model)) $resource = [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'variations' => VariationResource::collection($this->whenLoaded('variations')),
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'sale_price' =>  $this->salePrice(),
        ];
        if ($this->includeTimes($this->model)) {
            $resource['created_at'] = (new Carbon($this->created_at))->format('d-m-Y H:i:s');
            $resource['updated_at'] = (new Carbon($this->updated_at))->format('d-m-Y H:i:s');
            $resource['deleted_at'] = (new Carbon($this->updated_at))->format('d-m-Y H:i:s');
        }
        return $resource;
    }
}

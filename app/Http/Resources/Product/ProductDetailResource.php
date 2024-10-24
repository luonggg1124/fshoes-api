<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ResourceSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    use ResourceSummary;

    public static $wrap = false;
    private string $model = 'product_detail';

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
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'sale_price' => $this->salePrice(),
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'attributes' => $this->attributes,
            'images' => ImageResource::collection($this->images),
            'categories' => CategoryResource::collection($this->categories),
            'variations' => VariationResource::collection($this->variations),
            'currentDiscount' => new DiscountResource($this->currentDiscount()),
            'suggestedProduct' => ProductResource::collection($this->suggestedProduct),
        ];
        if ($this->shouldSummaryRelation($this->model))
            $resource = [
                'id' => $this->id,
                'image_url' => $this->image_url,
                'name' => $this->name,
                'slug' => $this->slug,
                'price' => $this->price,
                'sale_price' => $this->salePrice(),
                'attributes' => $this->attributes,
                'images' => ImageResource::collection($this->whenLoaded('images')),
                'categories' => CategoryResource::collection($this->whenLoaded('categories')),
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

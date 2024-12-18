<?php

namespace App\Http\Resources\Product;


use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Sale\SaleResource;
use App\Http\Traits\ResourceSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariationResource extends JsonResource
{
    use ResourceSummary;
    public static $wrap = false;
    private string $model = 'product_variation';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $resource = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'classify' => $this->classify,
            'price' => $this->price,
            'sale_price' => $this->salePrice(),
            'sku' => $this->sku,
            'status' => $this->status,
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
            'image_url' => count($this->images) > 0 ? $this->images[0]['url'] : $this->product->image_url,
            'qty_sale' => $this->saleQuantity(),
            'currentSale' => new SaleResource($this->currentSale()),
            'product' => new ProductResource($this->whenLoaded('product')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'values' => ValueResource::collection($this->whenLoaded('values')),
            
        ];
        if($this->shouldSummaryRelation($this->model)) $resource = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'price' => $this->price,
            'sale_price' => $this->salePrice(),
            'product' => new ProductResource($this->whenLoaded('product')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'values' => ValueResource::collection($this->whenLoaded('values')),
        ];
        if ($this->includeTimes($this->model))
        {
            $resource['created_at'] = $this->created_at;
            $resource['updated_at'] = $this->updated_at;
            $resource['deleted_at'] = $this->deleted_at;

        }
        return $resource;
    }
}

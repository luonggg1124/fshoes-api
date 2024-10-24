<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ResourceSummary;
use Carbon\Carbon;
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
            'price' => number_format($this->price, 0, ',', '.'),
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
            'description' => $this->description,
            'short_description' =>  $this->short_description,
            'created_at' => (new Carbon($this->created_at))->format('d-m-Y H:i:s'),
            'updated_at' => (new Carbon($this->updated_at))->format('d-m-Y H:i:s'),
            'images' =>  ImageResource::collection($this->images),
            'categories' => CategoryResource::collection($this->categories),
            'attributes' => $this->attributes,
            'variations' => VariationResource::collection($this->variations),
            'suggestedProduct' => ProductResource::collection($this->suggestedProduct),
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

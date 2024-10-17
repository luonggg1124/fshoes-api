<?php

namespace App\Http\Resources\Product;


use App\Http\Resources\Attribute\Value\ValueResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ProductResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariationResource extends JsonResource
{
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
            'product' => new ProductResource($this->whenLoaded('product')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'values' => ValueResource::collection($this->whenLoaded('values')),
            'price' => $this->price,
            'sku' => $this->sku,
            'status' => $this->status,
            'stock_qty' => $this->stock_qty,
            'qty_sold' => $this->qty_sold,
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

<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariations;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(
        Product                  $model,
        public ProductVariations $variations,
        public ProductImage      $image

    )
    {
        parent::__construct($model);

    }

    public function findBySlugOrId(string $column, string $value)
    {
        $product = $this->model->query()->when(
            $column === 'id',
            function ($query) use ($value) {
                $query->where('id', $value);
            },
            function ($query) use ($value) {
                $query->where('slug', $value);
            }
        )->first();
        if (!$product) {
            throw new ModelNotFoundException('Product not found');
        }
        return $product;
    }

    public function createVariations(array $data = [])
    {
        $variation = $this->variations->create($data);
        if (!$variation)
            throw new \Exception('Cannot create variation');

        return $variation;
    }

    public function updateVariations(int|string $id, array $data = [])
    {
        $variation = $this->variations->query()->findOrFail($id);
        if ($variation->update($data)) return $variation;
        return false;
    }

    public function createImage(array $data = [])
    {
        $image = $this->image->create($data);
        if (!$image) throw new \Exception('Cannot create product image');
        return $image;
    }

    public function updateImage(int|string $id, array $data = [])
    {
        $image = $this->image->query()->findOrFail($id);
        if ($image) return $image;
        return false;

    }


}

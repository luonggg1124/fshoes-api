<?php

namespace App\Repositories\Product;

use App\Models\Image;
use App\Models\Product;

use App\Models\ProductVariations;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(
        Product                  $model,
        public ProductVariations $variations,
        public Image      $image

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
        return $product;
    }

    public function createVariations(array $data = [])
    {
        return $this->variations->create($data);
    }

    public function updateVariations(int|string $id, array $data = [])
    {
        $variation = $this->variations->query()->findOrFail($id);
        if ($variation->update($data)) return $variation;
        return false;
    }

    public function createImage(array $data = [])
    {
        return $this->image->create($data);
    }

    public function updateImage(int|string $id, array $data = [])
    {
        $image = $this->image->query()->findOrFail($id);
        if ($image) return $image;
        return false;
    }


}

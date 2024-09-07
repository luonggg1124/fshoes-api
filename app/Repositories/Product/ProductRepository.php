<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariations;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(
        Product $model,
        public ProductVariations $variations,
        public ProductImage $image

    )
    {
        parent::__construct($model);
        
    }
    public function createVariations(array $data = []){
        try{
            $variation = $this->variations->create($data);
            return $variation;
        }catch(\Exception $e){
            throw new \Exception('Cannot create variation');
        }
    }
    public function updateVariations(int|string $id, array $data = []){
        $variation = $this->variations->query()->findOrFail($id);
        if($variation->update($data)) return $variation;
        return false;
    }

    public function createImage(array $data = []){
        try{
            $image = $this->image->create($data);
            return $image;
        }catch(\Exception $e){
            throw new \Exception('Cannot create product image');
        }
    }
    public function updateImage(int|string $id, array $data = []){
            $image = $this->image->query()->findOrFail($id);
            if ($image) return $image;
            return false;
        
    }
    

}

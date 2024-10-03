<?php

namespace App\Services\Product;

use App\Http\Traits\Paginate;
use App\Http\Traits\Cloudinary;

use App\Services\Image\ImageServiceInterface;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\ProductResource;
use App\Http\Traits\CanLoadRelationships;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService implements ProductServiceInterface
{
    use CanLoadRelationships, Cloudinary, Paginate;

    private array $relations = ['categories', 'images', 'variations'];
    private array $columns = [
        'name',
        'slug',
        'price',
        'short_description',
        'description',
        'sku',
        'status',
        'qty_sold',
        'stock_qty',
        'created_at',
        'updated_at',
    ];
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ImageServiceInterface $imageService,
    ) {
    }

    public function all()
    {
        $perPage = request()->query('per_page');
        $column = request()->query('column') ?? 'id';
        if(!in_array($column,$this->columns)) $column = 'id';
        $sort = request()->query('sort') ?? 'desc';
        if($sort !== 'desc' && $sort !== 'asc') $sort = 'asc';
        $products = $this->loadRelationships($this->productRepository->query()->orderBy($column,$sort)->latest())->paginate($perPage);
        return [
            'paginator' => $this->paginate($products),
            'data' => ProductResource::collection(
                $products->items()
            ),
        ];


    }
    public function findById(int|string $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new ModelNotFoundException('Product not found');
        }
        $product = $this->loadRelationships($product);
        return new ProductResource($product);
    }

    public function create(
        array $data,
        array $options = [
            'images' => []
        ]
    )
    {
        return DB::transaction(function () use ($data, $options) {
            $product = $this->productRepository->create($data);
            if(!$product) throw new \Exception('Cannot create product');
            $product->slug = $this->slug($product->name,$product->id);
            $product->save();
            if(count($options['images']) > 0){
                foreach ($options['images'] as $image) {
                    if($image instanceof UploadedFile){
                        $image = $this->imageService->create($image,'product');
                        $product->images()->attach($image->id);
                    }elseif (is_int($image)){
                        $product->images()->attach($image);
                    }
                }
            }
            return new ProductResource($this->loadRelationships($product));
        });
    }
    public function update(int|string $id, array $data,array $options=[
        'images' => []
    ])
    {
        return DB::transaction(function () use ($id,$data, $options) {
            $product = $this->productRepository->find($id);

            if(!$product) throw new \Exception('Product not found');
            $product->update($data);
            $product->slug = $this->slug($product->name,$product->id);
            $product->save();
            if(count($options['images']) > 0){
               $product->images()->sync($options['images']);
            }
            return new ProductResource($this->loadRelationships($product));
        });
    }


    protected function slug(string $name, int|string $id){
        $slug = Str::slug($name).'.'.$id;
        $exists = $this->productRepository->query()->where('slug',$slug)->exists();
        if($exists){
            return Str::slug($name).'-'.Str::random(2).'.'.$id;
        }
        return $slug;
    }
}




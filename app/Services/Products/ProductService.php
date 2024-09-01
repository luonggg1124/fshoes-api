<?php
namespace App\Services\Products;
use Exception;
use App\Services\Products\ProductServiceInterface;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Repositories\Products\ProductRepositoryInterface;
use App\Repositories\Attribute\AttributesRepositoryInterface;
use App\Repositories\ProductImages\ProductImagesRepositoryInterface;
use App\Repositories\AttributeValues\AttributeValuesRepositoryInterface;
use App\Repositories\ProductVariations\ProductVariationsRepositoryInterface;


class ProductService implements ProductServiceInterface{

    protected $productRepository;
    protected $attributesRepository;
    protected $attributeValuesRepository;
    protected $productVariationsRepository;
    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributesRepositoryInterface $attributesRepository,
        AttributeValuesRepositoryInterface $attributeValuesRepository,
        ProductVariationsRepositoryInterface $productVariationsRepository
    ) {
        $this->productRepository = $productRepository;
        $this->attributesRepository = $attributesRepository;
        $this->attributeValuesRepository = $attributeValuesRepository;
        $this->productVariationsRepository = $productVariationsRepository;
    }
    public function getAllProducts(){
        return $this->productRepository->all();
    }
    public function getProductById($id){
            return $this->productRepository->findById($id);
    }
    public function createProduct(array $data){
        try{
            //add product and get id
            $product =  $this->productRepository->create($data);
            if(isset($data['is_variant']) && isset($data['product_variations'])){
                    //add variant first   
                    foreach(json_decode($data["product_variations"]) as $variation ){
                            $product_variation_value = $variation['product_variation_value'];
                            unset($variation['product_variation_value']);
                            $variation["product_id"]= $product->id;
                            $newVariation = $this->productVariationsRepository->create($variation);
                            if($variation['image_url']){
                                $cloudinaryImage = new Cloudinary();
                                $cloudinaryImage =$this->image->storeOnCloudinary('product_variations');
                                $url = $cloudinaryImage->getSecurePath();
                                $newVariation->image_url = $url;
                                $newVariation->save();
                            }
                            //"product_variation_value"=> {
                            //     "color"=>"red",
                            //     "size"=>"M",
                            //     "country"=>"US"
                            // }
                            foreach($product_variation_value as $key=>$value){
                                    //Create or get attribute
                                    $attribute = $this->attributesRepository->findByName($key) ?? $this->attributesRepository->create(["name"=>$key]);
                                    $this->attributeValuesRepository->create([
                                        'attribute_id'=>$attribute->id,
                                        'value'=>$value
                                    ]);
                            }
                    }
            }
            return $product;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function updateProduct($id, array $data){
        return $this->productRepository->update($id, $data);       
    }
    public function deleteProduct($id){
        return $this->productRepository->delete($id);
    }
    public function restoreProduct($id){    
        return $this->productRepository->restore($id);
    }
    public function forceDeleteProduct($id){
        return $this->productRepository->forceDelete($id);
    }
}
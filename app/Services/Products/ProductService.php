<?php
namespace App\Services\Products;

use App\Repositories\Attribute\AttributesRepositoryInterface;
use App\Repositories\AttributeValues\AttributeValuesRepositoryInterface;
use App\Repositories\ProductImages\ProductImagesRepositoryInterface;
use App\Repositories\ProductVariations\ProductVariationsRepositoryInterface;
use Exception;
use App\Services\Products\ProductServiceInterface;
use App\Repositories\Products\ProductRepositoryInterface;


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
            $product =  $this->productRepository->create($data['product']);
            if($data['is_variant']){
                    //add variant first
                    foreach($data["product_variations"] as $variation ){
                            $product_variation_value = $variation['product_variation_value'];
                            unset($variation['product_variation_value']);

                            $variation["product_id"]= $product;
                            $newVariation = $this->productVariationsRepository->create($variation);
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
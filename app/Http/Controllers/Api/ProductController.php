<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductImage;
use Exception;
use Illuminate\Http\Request;
use App\Jobs\UploadProductImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Services\Products\ProductServiceInterface;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Symfony\Component\HttpFoundation\Response as Status;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductServiceInterface $productService) {
        $this->productService = $productService;
    }
    public function index()
    {
           $products =  $this->productService->getAllProducts();
           return response()->json($products, Status::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $input = $request->all();
        $newProduct =  $this->productService->createProduct($input);
        //Add Image
        if ($request->hasFile('product_images')) {
            if (is_array($request->file('product_images'))) {
                $images = $request->file('product_images');
            } else {
                $images = [$request->file('product_images')];
            }

            foreach ($images ?? [] as $key=> $image) {
                $cloudinaryImage = new Cloudinary();
                $cloudinaryImage = $image->storeOnCloudinary('products');
                $url = $cloudinaryImage->getSecurePath();
                $public_id = $cloudinaryImage->getPublicId();

                ProductImage::insert([
                    "product_id"=>$newProduct->id,
                    "image_url"=>$url,
                    "alt_text"=>"Product Image",
                    "id_image"=>$public_id
                ]);
            }
        }
        return response()->json($newProduct, Status::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product =  $this->productService->getProductById($id);
        return response()->json($product, Status::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $newProduct =  $this->productService->updateProduct($id,$input);
        return response()->json($newProduct, Status::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $softDelete = $this->productService->deleteProduct($id);
        return response()->json($softDelete , Status::HTTP_OK);
    }

    public function restore(string $id)
    {
        $restore = $this->productService->restoreProduct($id);
        return response()->json($restore , Status::HTTP_OK);
    }
    public function forceDelete(string $id)
    {
        try{
            $forceDelete = $this->productService->forceDeleteProduct($id);
            return response()->json($forceDelete , Status::HTTP_OK);
        }catch(Exception $e){
            response()->json($e->getMessage() , Status::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }
}

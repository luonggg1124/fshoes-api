<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Jobs\UploadProductImage;
use App\Http\Controllers\Controller;
use App\Services\Products\ProductServiceInterface;
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
        if($request->product_images){
            $second  =0;
            foreach($request->product_images as $image){
                UploadProductImage::dispatch($image, $newProduct->id)->delay(now()->addSeconds(5+$second));
                $second+=3;
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
        //
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
        $forceDelete = $this->productService->forceDeleteProduct($id);
        return response()->json($forceDelete , Status::HTTP_OK);
    }
}

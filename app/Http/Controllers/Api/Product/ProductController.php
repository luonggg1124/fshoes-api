<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{


    public function __construct(protected ProductServiceInterface $productService){}
    /**
     * Display a listing of the resource.
     */
    public function index():Response|JsonResponse

    {
        return response()->json(
            $this->productService->all()
        );
    }


    public function store(CreateProductRequest $request)
    {

         try{

             $data = $request->all();
             $images = $request->images ?? [];
             $categories = $request->categories ?? [];
             $data['qty_sold'] = 0;
             $product = $this->productService->create($data,[
                 'images' => $images,
                 'categories' => $categories
             ]);
             return response()->json([
                 'message' => __('messages.created-success'),
                 'status' => true,
                 'product' => $product
             ],201);
         }catch(\Throwable $throwable){
             Log::error(
                 message: __CLASS__.'@'.__FUNCTION__,context: [
                 'line' => $throwable->getLine(),
                 'message' => $throwable->getMessage()
             ]
             );
             return response()->json([
                 'message' => __('messages.error-internal-server'),
                 'status' => false,
             ],500);
         }

    }
    
    
    

    public function createAttributeValues(int $id,Request $request):Response|JsonResponse
    {
        try {
            if(empty($request->attribute)){
                return \response()->json([
                    'status' => false,
                    'error' => __('messages.error-required'),
                ],400);
            }
            if(empty($request->values)){
                return \response()->json([
                    'status' => false,
                    'error' => __('messages.error-required'),
                ],400);
            }elseif (!is_array($request->values)){
                return \response()->json([
                    'status' => false,
                    'error' => __('messages.error-value'),
                ],400);
            }
            $attribute = $request->attribute;
            $values = $request->values;
            $data = $this->productService->createAttributeValues($id,$attribute,$values);
            return \response()->json(
                $data
            ,201);
        }catch (\Throwable $throw){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throw->getLine(),
                'message' => $throw->getMessage()
            ]
            );
            if ($throw instanceof ModelNotFoundException){
                return \response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ],404);
            }
            return \response()->json([
                'status' => false,
                'error' => $throw->getMessage()
            ],500);
        }
    }
    public function getAttributeValues(int|string $id){
        try {
            return response()->json($this->productService->productAttribute($id));
        }catch (\Throwable $throw){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throw->getLine(),
                'message' => $throw->getMessage()
            ]
            );
            return response()->json([
                'error' => $throw->getMessage(),
                'status' => false
            ], 404);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string|int $id):Response|JsonResponse
    {
        try {
            return response()->json($this->productService->findById($id));
        }catch (ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false
            ], 404);
        }
    }
    public function productDetail(string|int $id){
        try {
            return response()->json($this->productService->productDetail($id));
        }catch (\Throwable $throw){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throw->getLine(),
                'message' => $throw->getMessage()
            ]
            );
            return response()->json([
                'error' => $throw->getMessage(),
                'status' => false
            ], 404);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string|int $id)
    {
        try{
            $data = $request->all();
            $images = $request->images ?? [];
            $categories = $request->categories ?? [];
            $product = $this->productService->update($id,$data,[
                'images' => $images,
                'categories' => $categories
            ]);
            return response()->json([
                'message' => __('messages.update-success'),
                'status' => true,
                'product' => $product
            ],201);
        }catch(\Throwable $throwable){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage()
            ]
            );
            return response()->json([
                'message' => __('messages.error-internal-server'),
                'status' => false,
            ],500);
        }
    }
    public function productsByCategory(int|string $categoryId){
        return response()->json([
            'status' => true,
            'products' => $this->productService->productByCategory($categoryId),
        ]);
    }
    public function updateProductStatus(Request $request, string|int $id){
        try {
            $status = $request->status;
            $product = $this->productService->updateStatus($status,$id);
            return response()->json([
                'message' => __('messages.update-success'),
                'status' => true,
            ],201);
        }catch (\Throwable $throwable){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage()
            ]
            );
            return response()->json([
                'message' => __('messages.error-internal-server'),
                'status' => false
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int|string $id)
    {
        try {
            $this->productService->destroy($id);
            return \response()->json([
                'status' => true,
                'message' => __('messages.delete-success'),
            ]);
        }catch (\Throwable $throw){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throw->getLine(),
                'message' => $throw->getMessage()
            ]
            );
            if($throw instanceof ModelNotFoundException){
                return \response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ],404);
            }
            return \response()->json([
                'status' => false,
                'error' => __('messages.error-internal-server'),
            ],500);
        }
    }

    public function productWithTrashed(){
        return \response()->json([
            'status' => true,
            'products' => $this->productService->productWithTrashed()
        ],200);
    }
    public function productTrashed(){
        return \response()->json([
            'status' => true,
            'products' => $this->productService->productTrashed()
        ],200);
    }
    public function getOneTrashed(int|string $id){
        try {
            return response()->json($this->productService->findProductTrashed($id));
        }catch (ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false
            ], 404);
        }
    }
    public function restore(int|string $id){
        try {
            $product = $this->productService->restore($id);
            return \response()->json([
                'status' => true,
                'product' => $product,
                'message' => __('messages.restore-success'),
            ],201);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false
            ], 404);
        }
    }
    public function forceDestroy(int|string $id){
        try {
            $sucess = $this->productService->forceDestroy($id);
            return \response()->json([
                'status' => $sucess,
                'message' => __('messages.delete-success'),

            ]);
        }catch (\Throwable $throwable){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage()
            ]
            );
            if($throwable instanceof ModelNotFoundException){
                return \response()->json([
                    'status' => false,
                    'error' => $throwable->getMessage()
                ],404);
            }
            return response()->json([
                'status' => false,
                'error' => __('messages.error-internal-server'),
            ],500);
        }
    }

    public function productsByAttributeValues(){
        return \response()->json([
            'status' => true,
            'products' => $this->productService->findByAttributeValues()
        ]);
    }

    public function allSummary(){
        return \response()->json([
            'status' => true,
            'products' => $this->productService->allSummary()
        ]);
    }
    
}

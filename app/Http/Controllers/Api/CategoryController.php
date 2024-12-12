<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\Category\CategoryServiceInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CategoryController extends Controller
{

    public function __construct(protected CategoryServiceInterface $categoryService)
    {}
    public function index():Response|JsonResponse
    {

        return response()->json([
                'status' => true,
                'categories' =>$this->categoryService->getAll()
            ]);
    }
    public function mains():JsonResponse
    {
        return response()->json([
            'status' => true,
           'categories' => $this->categoryService->mains()
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request):Response|JsonResponse
    {

        try {
            $category = $this->categoryService->create($request->validated(),[
               'parents' => $request->parents
            ]);
            return response()->json([
                'message' => 'Category created successfully',
                'category' => $category
            ],201);
        }catch (\Throwable $throw){

            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if($throw instanceof ModelNotFoundException){
                return response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ], 404);
            }
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong'
            ], 500);
        }
    }
    public function addProducts(Request $request,int|string $id):Response|JsonResponse
    {
        try {
            $products = $request->products;
            if(!$products || !is_array($products)) return response()->json([
                'status' => false,
                'error' => 'Products not found'
            ],422);

            $category = $this->categoryService->addProducts($id,$products);
            return response()->json([
                'status' => true,
                'message' => 'Products added successfully',
                'category' => $category
            ],201);
        }catch (\Throwable $throw){
            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if($throw instanceof ModelNotFoundException){
                return response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ],404);
            }
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong'
            ],500);
        }
    }
    public function deleteProducts(Request $request,int|string $id):Response|JsonResponse
    {
        try {
            $products = $request->products;
            if(!$products || !is_array($products)) return response()->json([
                'status' => false,
                'error' => 'Products not found'
            ],422);

            $category = $this->categoryService->deleteProducts($id,$products);
            return response()->json([
                'status' => true,
                'message' => 'Deleted successfully!',
                'category' => $category,
            ],201);
        }catch (\Throwable $throw){
            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if($throw instanceof ModelNotFoundException){
                return response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ],404);
            }
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong'
            ],500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(int $id):Response|JsonResponse
    {
        try {
            return response()->json($this->categoryService->findById($id));
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, int $id):Response|JsonResponse
    {
        try {
            $category = $this->categoryService->update($id,$request->validated(),[
                'parents'=> $request->parents
            ]);
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category
            ],201);
        }catch (\Throwable $throw){

            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if($throw instanceof ModelNotFoundException){
                return response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ], 404);
            }
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id):Response|JsonResponse
    {
        try {
            $this->categoryService->delete($id);
            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully',
            ]);
        }catch (ModelNotFoundException $e){
            logger('error',[
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 404);
        }catch(AuthorizationException $e){
            logger('error',[
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
               'status' => false,
                'error' => $e->getMessage()
            ], 403);
        }catch(Exception $e){
            logger('error',[
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong'
            ], 500);
        }catch(Throwable $throw){
            logger('error',[
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error' => 'System Error',
            ], 500);
        }

    }

    public  function forceDelete(int|string $id):Response|JsonResponse
    {
        try {
            $this->categoryService->forceDelete($id);
            return response()->json([
                'message' => 'The category has been permanently deleted.',
            ]);
        }catch (ModelNotFoundException $e){
            logger('error',[
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 404);
        }catch(AuthorizationException $e){
            logger('error',[
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
               'status' => false,
                'error' => $e->getMessage()
            ], 403);
        }catch(Exception $e){
            logger('error',[
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong'
            ], 500);
        }catch(Throwable $throw){
            logger('error',[
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            return response()->json([
                'status' => false,
                'error' => 'System Error',
            ], 500);
        }
    }


}

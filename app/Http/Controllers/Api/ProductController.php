<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->variations && $request->variations[0] != null){
            dd($request->all());
        }
        
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id):Response|JsonResponse
    {
        try {
            return response()->json($this->productService->findById($id));
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
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
        //
    }
}

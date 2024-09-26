<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
    public function store(CreateProductRequest $request)
    {


         try{
             $data = $request->all();
             $images = [];
             if($request->has('images')) $images = $request->images;
             $data['qty_sold'] = 0;
             return $this->productService->create($data,[
                 'images' => $images
             ]);
         }catch(\Exception $e){
             return response()->json(['message' => $e->getMessage()],500);
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

    public function generateQRProduct(string|int $id)
    {
        $product = $this->productService->findById($id);
        if(!$product) return response()->json(['error' => 'Product not found'], 404);
        $qrCode = QrCode::format('png')->size(300)->generate(route('product.show',['id' => $id]));
        return response()->make($qrCode,200,[
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment;filename='.$product->slug??'image'.'.png'
        ]);
    }
}

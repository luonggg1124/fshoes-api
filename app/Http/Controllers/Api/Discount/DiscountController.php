<?php

namespace App\Http\Controllers\Api\Discount;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\CreateSaleRequest;
use App\Http\Requests\Discount\UpdateSaleRequest;
use App\Services\Discount\DiscountServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function __construct(protected DiscountServiceInterface $service)
    {
    }

    public function index():Response|JsonResponse
    {
        return response()->json([
            'status' => true,
            ...$this->service->all()
        ]);
    }

    public function show(int|string $id):Response|JsonResponse
    {
        try {
            $discount = $this->service->show($id);
            return response()->json([
                'status' => true,
                'discount' => $discount
            ]);
        }catch (\Throwable $throw) {
            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if ($throw instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => $throw->getMessage()
                ], 404);
            }

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }

    }
    public function store(CreateSaleRequest $request):Response|JsonResponse
    {

        try {
            if(isset($data['type']) && $data['type'] === 'percent'){
                if($data['type'] > 99 || $data['type'] < 1){
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type',

                    ],422);
                }
            }
            $data = $request->only(['name','type','value','is_active','start_date','end_date']);
            $products = $request->products;
            $variations = $request->variations;

            $discount = $this->service->store($data,[
                'products' => $products,
                'variations' => $variations
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Create discount successfully.',
                'discount' => $discount
            ],201);
        } catch (\Throwable $throw) {
            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if ($throw instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => $throw->getMessage()
                ], 404);
            }

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
    public function update(UpdateSaleRequest $request,int|string $id):Response|JsonResponse
    {
        try {
            if(isset($data['type']) && $data['type'] === 'percent'){
                if($data['type'] > 99 || $data['type'] < 1){
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type',

                    ],422);
                }
            }
            $data = $request->only(['name','type','value','is_active','start_date','end_date']);
            $products = $request->products;
            $variations = $request->variations;

            $discount = $this->service->update($id,$data,[
                'products' => $products,
                'variations' => $variations
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Updated discount successfully.',
                'discount' => $discount
            ],201);
        } catch (\Throwable $throw) {
            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if ($throw instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => $throw->getMessage()
                ], 404);
            }

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }


    }
    public function destroy(int|string $id):Response|JsonResponse
    {
        try {
            $status = $this->service->destroy($id);
            return response()->json([
                'status' => $status,
                'message' => 'Delete discount successfully.'
            ]);
        }catch (\Throwable $throw)
        {
            Log::error('Some thing went wrong!', [
                'message' => $throw->getMessage(),
                'file' => $throw->getFile(),
                'line' => $throw->getLine(),
                'trace' => $throw->getTraceAsString(),
            ]);
            if ($throw instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => $throw->getMessage()
                ], 404);
            }

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}

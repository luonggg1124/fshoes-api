<?php

namespace App\Http\Controllers\Api\Product\Variation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Variation\CreateVariationRequest;
use App\Http\Requests\Product\Variation\UpdateVariationRequest;
use App\Services\Product\Variation\VariationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VariationController extends Controller
{
    public function __construct(
        protected VariationService $service
    ){}
    public function index(string|int $pid){
        return response()->json([
           'status' => true,
           'variations' => $this->service->index($pid)
        ]);
    }
    public function store(string|int $pid, CreateVariationRequest $request){
        try {
            $variations = $request->variations;

            if(empty($variations) || !is_array($variations) ||count($variations) < 1){
                return response()->json([
                    'status' => false,
                    'message' => 'Malformed variant.'
                ],400);
            }
            $list = $this->service->createMany($pid,$variations);
            return response()->json([
                'status' => true,
                'variations' => $list
            ],201);
        }catch (\Throwable $throwable){
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage()
            ]
            );
            if($throwable instanceof ModelNotFoundException){
                return response()->json([
                    'status' => false,
                    'message' => $throwable->getMessage()
                ],404);
            }
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ],500);
        }
    }

    public function update(string|int $pid, int|string $id,UpdateVariationRequest $request){

    }
}

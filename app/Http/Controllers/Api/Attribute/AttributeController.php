<?php

namespace App\Http\Controllers\Api\Attribute;

use App\Http\Controllers\Controller;
use App\Services\Attribute\AttributeServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class AttributeController extends Controller
{

    public function __construct(protected AttributeServiceInterface $attributeService){}
    /**
     * Display a listing of the resource.
     */
    public function index():Response|JsonResponse
    {
        return \response()->json([
           $this->attributeService->all()
        ]);
    }


    public function store(Request $request):Response|JsonResponse
    {
        try {
            if(empty($request->name)){
                return \response()->json([
                    'status' => false,
                    'error' => __('messages.error-required'),
                ],422);
            }
            $data = [
                'name' => $request->get('name')
            ];
            $attribute = $this->attributeService->create($data);
            return \response()->json([
                'status' => true,
                'attribute' => $attribute
            ]);

        }catch (\Throwable $throw){
            return \response()->json([
                'status' => false,
                'error' => $throw->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int|string $id)
    {
        try {
            $attribute = $this->attributeService->find($id);
            return \response()->json([
                'status' => true,
                'attribute' => $attribute
            ]);
        }catch (\Throwable $throw){
            return \response()->json([
                'error' => $throw->getMessage(),
                'status' => false
            ],400);
        }
    }



    public function update(Request $request, int|string $id)
    {
        try {
            if(empty($request->name)){
                return \response()->json([
                    'status' => false,
                    'error' => __('messages.error-required'),
                ],422);
            }
            $data = [
                'name' => $request->get('name')
            ];
            $attribute = $this->attributeService->update($id,$data);
            return \response()->json([
                'status' => true,
                'attribute' => $attribute
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
                   $throw->getMessage()
                ],404);
            }
            return \response()->json([
                'status' => false,
                'error' => $throw->getMessage()
            ],500);
        }
    }


    public function destroy(int|string $id)
    {
        try {
            DB::transaction(function () use ($id){
                $this->attributeService->delete($id);
            });
            return \response()->json([
                'status' => true,
                'message' => __('messages.delete-success'),
            ],201);
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
            if($throw instanceof InvalidArgumentException){
                return response()->json([
                    'status' => false,
                    'message' => $throw->getMessage()
                ], 422);
            }
            return \response()->json([
                'status' => false,
                'error' => __('messages.error-internal-server'),
            ],500);
        }
    }

    public function isFilterAttributes() {
        try {
            $attributes = $this->attributeService->isFilterAttributes();
            return \response()->json([
                'status' => true,
                'attributes' => $attributes
            ]);
        }catch (\Exception $e){
            return \response()->json([
                'status' => false,
                'error' => __('messages.error-internal-server'),
            ],500);
        }
    }
}

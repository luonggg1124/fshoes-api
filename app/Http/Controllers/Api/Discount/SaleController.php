<?php

namespace App\Http\Controllers\Api\Discount;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\CreateSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Services\Sale\SaleServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function __construct(protected SaleServiceInterface $service)
    {
    }

    public function index()
    {
        return \response()->json([
            'status' => true,
            'data' => [...$this->service->all()]
        ]);

    }

    public function stream()
    {
        return response()->stream(function () {
            while (true) {
                $sales = $this->service->all();
                echo "data:" . json_encode($sales) . "\n\n";
                ob_flush();
                flush();
                sleep(1);
                if (connection_aborted()) {
                    break;
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    public function switchActive(Request $request, int|string $id)
    {
        try {
            $active = $request->active;
            $this->service->switchActive($id,$active);
            return response()->json([
                'status' => true,
                'message' => 'Update successfully!'
            ],201);
        } catch (ModelNotFoundException $e) {
            return \response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
    public function show(int|string $id): Response|JsonResponse
    {
        try {
            $discount = $this->service->show($id);
            return response()->json([
                'status' => true,
                'discount' => $discount
            ]);
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

    public function store(CreateSaleRequest $request): Response|JsonResponse
    {

        try {
            $data = $request->only(['name', 'type', 'value', 'is_active', 'start_date', 'end_date','applyAll']);
            if (isset($data['type']) && $data['type'] === 'percent') {
                if ($data['value'] > 99 || $data['value'] < 1) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type',
                    ], 422);
                }
            }
            $products = $request->products;
            $variations = $request->variations;
            $applyAll = $request->applyAll;
            $discount = $this->service->store($data, [
                'products' => $products,
                'variations' => $variations,
                'applyAll' => $applyAll
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Create discount successfully.',
                'discount' => $discount
            ], 201);
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

    public function update(UpdateSaleRequest $request, int|string $id): Response|JsonResponse
    {
        try {
            
            $data = $request->only(['name', 'type', 'value', 'is_active', 'start_date', 'end_date']);
            if (isset($data['type']) && $data['type'] === 'percent') {
                if ($data['type'] > 99 || $data['type'] < 1) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid type',

                    ], 422);
                }
            }
            $products = $request->products;
            $variations = $request->variations;

            $discount = $this->service->update($id, $data, [
                'products' => $products,
                'variations' => $variations
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Updated discount successfully.',
                'discount' => $discount
            ], 201);
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

    public function destroy(int|string $id): Response|JsonResponse
    {
        try {
            $status = $this->service->destroy($id);
            return response()->json([
                'status' => $status,
                'message' => 'Delete discount successfully.'
            ]);
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
}

<?php

namespace App\Http\Controllers\Api\Attribute\Value;

use App\Http\Controllers\Controller;

use App\Services\Attribute\Value\AttributeValueServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttributeValueController extends Controller
{
    public function __construct(
        protected AttributeValueServiceInterface $service,
    )
    {
    }

    public function index(int|string $aid)
    {
        return \response()->json([
            'status' => true,
            'values' => $this->service->index($aid)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string|int $aid)
    {
        try {
            if (empty($request->values)) {
                return \response()->json([
                    'status' => false,
                    'error' => 'The values is required'
                ], 422);
            }
            $data = $request->values;
            $values = $this->service->createMany($aid, $data);
            return \response()->json([
                'status' => true,
                'values' => $values,
            ], 201);

        } catch (\Throwable $throw) {
            Log::error(
                message: __CLASS__.'@'.__FUNCTION__,context: [
                'line' => $throw->getLine(),
                'message' => $throw->getMessage()
            ]
            );
            if ($throw instanceof ModelNotFoundException) {
                return \response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ], 404);
            }
            return \response()->json([
                'status' => false,
                'error' => 'Something went wrong.Please try later!'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $aid, string|int $id)
    {
        try {
            $value = $this->service->find($aid, $id);
            return \response()->json([
                'status' => true,
                'value' => $value
            ]);
        } catch (\Throwable $throwable) {
            return \response()->json([
                'status' => false,
                'error' => $throwable->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string|int $aid, string|int $id)
    {
        try {
            if (empty($request->value)) {
                return \response()->json([
                    'status' => false,
                    'error' => 'The value is required'
                ], 422);
            }
            $data = [
                'value' => $request->get('value')
            ];
            $value = $this->service->update($aid, $id, $data);
            return \response()->json([
                'status' => true,
                'value' => $value,
            ], 201);

        } catch (\Throwable $throw) {
            if ($throw instanceof ModelNotFoundException) {
                return \response()->json([
                    'status' => false,
                    'error' => $throw->getMessage()
                ], 404);
            }
            return \response()->json([
                'status' => false,
                'error' => 'Something went wrong.Please try later!'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int|string $aid,string|int $id)
    {
        try {
            $success = $this->service->delete($aid, $id);
            return \response()->json([
                'status' => $success,
                'message' => 'Deleted successfully!'
            ],201);
        }catch (\Throwable $throwable) {
            return \response()->json([
                'status' => false,
                'error' => $throwable->getMessage()
            ],404);
        }
    }
}

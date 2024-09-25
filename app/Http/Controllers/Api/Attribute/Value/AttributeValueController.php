<?php

namespace App\Http\Controllers\Api\Attribute\Value;

use App\Http\Controllers\Controller;

use App\Services\Attribute\Value\AttributeValueServiceInterface;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function __construct(
        protected AttributeValueServiceInterface $service,
    )
    {
    }
    public function index()
    {
        return \response()->json([
            $this->service->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,string|int $aid)
    {
        try {
            if(empty($request->value)){
                return \response()->json([
                    'status' => false,
                    'error' => 'The value is required'
                ],422);
            }
            $data = [
                'value' => $request->get('value')
            ];
            $value = $this->service->create($aid,$data);
            return \response()->json([
                'status' => true,
                'value' => $value
            ]);

        }catch (\Throwable $throw){
            return \response()->json([
                'status' => false,
                'error' => 'Something went wrong.Please try later!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id)
    {
        //
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

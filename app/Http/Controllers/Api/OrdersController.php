<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected OrderService $orderService)
    {}
    public function index(Request $request)
    {
        
        return response()->json(
            $this->orderService->getAll($request) ,200
         );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        try {
            $newOrder = $this->orderService->create($request->all());
            return response()->json([
                'message' => 'Create order successfully',
                'cart' => $newOrder
            ],201);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json($this->orderService->findById($id) ,200);
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $order = $this->orderService->update($id, $request->all());
            return response()->json([
                'message' => 'Update order successfully',
                'order' => $order
            ],200);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}

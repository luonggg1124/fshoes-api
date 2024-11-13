<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        if (auth('api')->check() && auth('api')->user()->group_id > 1 && !Gate::allows('order.view')) {
            return response()->json(["message" => "You are not allowed to do this action."], 403);
        }
        return response()->json(
            $this->orderService->getAll($request), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {

        if (auth('api')->check() && auth('api')->user()->group_id > 1 && !Gate::allows('order.create')) {
            return response()->json(["message" => "You are not allowed to do this action."], 403);
        }

        return $this->orderService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (auth('api')->check() && auth('api')->user()->group_id > 1 && !Gate::allows('order.detail')) {
            return response()->json(["message" => "You are not allowed to do this action."], 403);
        }

        try {
            return response()->json($this->orderService->findById($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(Request $request, string $id)
    {
        if (auth('api')->check() && auth('api')->user()->group_id > 1 && !Gate::allows('order.update')) {
            return response()->json(["message" => "You are not allowed to do this action."], 403);
        }

        return $this->orderService->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
    public function me(Request $request){
        return response()->json(
            $this->orderService->me($request->all()) ,200
        );
    }
    public function cancelOrder(Request $request , $id){
        return $this->orderService->cancelOrder($id , $request->all());
    }
    public function search(Request $request)
    {
        return $this->orderService->search($request->all());
    }
}

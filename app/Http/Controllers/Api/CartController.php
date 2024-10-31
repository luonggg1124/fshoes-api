<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Cart\AddCartRequest;
use Illuminate\Http\Request;
use App\Services\Cart\CartService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CartController extends Controller
{


    public function __construct(protected CartService $cartService)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->cartService->getAll($request) ,200
         );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCartRequest $request)
    {
        try {
            $newCart = $this->cartService->create($request->all());
            return response()->json([
                'message' => 'Create cart successfully',
                'cart' => $newCart
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
            return response()->json($this->cartService->findById($id) ,200);
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
            $cart = $this->cartService->update($id, $request->all());
            return response()->json([
                'message' => 'Update cart successfully',
                'cart' => $cart
            ],201);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->cartService->delete($id);
            return response()->json([
                'message' => 'The cart has been permanently deleted.',
            ] ,200);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

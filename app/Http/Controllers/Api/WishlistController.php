<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\WishListRequest;
use App\Services\Wishlist\WishlistServiceInterface;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(protected WishlistServiceInterface $wishlistService)
    {
    }

    public function index(Request $request)
    {
        if ($request->user_id) {
            return response()->json($this->wishlistService->findByUserID($request->user_id), 200);
        }
        return response()->json($this->wishlistService->getAll(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WishListRequest $request)
    {
        try{
            return response()->json($this->wishlistService->create($request->all()) , 201);
        }catch (\Exception $e){
            return response()->json(["message" => "Can't add wishlist"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        try{
            if($this->wishlistService->delete($id)){
                return response()->json(["message"=>"Successful"] , 200);
            }else throw new \Exception("Can't delete wishlist");
        }catch (\Exception $e){
            return response()->json(["message" => "Can't delete wishlist"], 500);
        }
    }
}

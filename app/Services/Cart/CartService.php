<?php

namespace App\Services\Cart;

use App\Http\Resources\CartCollection;
use App\Repositories\Cart\CartRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;

class CartService implements CartServiceInterface {

    public function __construct(protected CartRepositoryInterface $cartRepository){}

    function getAll(){
        $allCart = $this->cartRepository->query()->with(['product' , 'product_variation' , 'product_variation.product']);
        $user = request()->user();
        if(!$user){
           throw new UnauthorizedException('Unauthorized!');
        }
        $allCart->where('user_id' , $user->id);
        $allCart->latest();
        return CartCollection::collection(
            $allCart->paginate()
        );
    }
    function findById(int|string $id){
        $cart = $this->cartRepository->query()->where('id', $id)->with(["product","product_variation", 'product_variation.product'])->first();
        if(!$cart){
            throw new ModelNotFoundException('Cart not found');
        }
        return new CartCollection($cart);
    }
    function create(array $data, array $option = []){
        try {
           $cart = $this->cartRepository->query()->where('user_id' , $data['user_id']);
           if(!isset($data['product_variation_id']) && !isset($data['product_id'])){
             throw new \Exception('Cannot add new cart');
           }

           if(isset($data['product_id'])){
               $cart = $cart->where('product_id', $data['product_id'])->first();
            }else{
                $cart =   $cart->where('product_variation_id', $data['product_variation_id'])->first();
            }

            if($cart){
                    $cart->quantity += $data['quantity'];
                    $cart->save();
                    return $cart;
            }else{
                    return $this->cartRepository->create($data);
            }
        }catch (\Exception $e){
            throw new \Exception('Cannot add new cart');
        }
    }
    function update(int|string $id,array $data, array $option = []){
        try {
            $cart = $this->cartRepository->find($id);
            $cart->quantity = $data['quantity'];
            $cart->save();
            return $cart;        
         }catch (\Exception $e){
             throw new \Exception('Cannot update  cart');
         }
    }
    function delete(int|string $id){
        try {
            $cart = $this->cartRepository->find($id);
            if($cart) $cart->delete($id);
            else  throw new \Exception('Not found');
        }catch (\Exception $e){
            throw new \Exception('Cannot delete cart');
        }
    }
}
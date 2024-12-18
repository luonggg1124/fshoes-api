<?php

namespace App\Services\Cart;

use App\Http\Resources\CartCollection;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\Variation\VariationRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;

class CartService implements CartServiceInterface
{

    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected ProductRepositoryInterface $productRepository,
        protected VariationRepositoryInterface $variationRepository
    ) {}

    function getAll()
    {
        $allCart = $this->cartRepository->query()->with(['product', 'product_variation', 'product_variation.product']);
        $user = request()->user();
        if (!$user) {
            throw new UnauthorizedException(__('messages.cart.error-cart'));
        }
        $allCart->where('user_id', $user->id);
        $allCart->latest();
        return CartCollection::collection(
            $allCart->paginate()
        );
    }
    function findById(int|string $id)
    {
        $cart = $this->cartRepository->query()->where('id', $id)->with(["product", "product_variation", 'product_variation.product'])->first();
        if (!$cart) {
            throw new ModelNotFoundException(__('messages.error-not-found'));
        }
        return new CartCollection($cart);
    }
    function create(array $data, array $option = [])
    {
        try {
            $cart = $this->cartRepository->query()->where('user_id', $data['user_id']);
            if (!isset($data['product_variation_id']) && !isset($data['product_id'])) {
                throw new \Exception(__('messages.cart.error-cart-add'));
            }
            if (isset($data['product_id'])) {
                $product = $this->productRepository->find($data['product_id']);
                if (!$product) {
                    throw new ModelNotFoundException(__('messages.error-not-found'));
                }
                if ($product->stock_qty == 0 || $data['quantity'] > $product->stock_qty) {
                    throw new \Exception('Product out of stock');
                }
            }
            if (isset($data['product_variation_id'])) {
                $variation = $this->variationRepository->find($data['product_variation_id']);
                if (!$variation) {
                    throw new ModelNotFoundException('Product not found');
                }
                if ($variation->stock_qty == 0 || $data['quantity'] > $variation->stock_qty) {
                    throw new \Exception('Product out of stock');
                }
            }
            if (isset($data['product_id'])) {
                $cart = $cart->where('product_id', $data['product_id'])->first();
            } else {
                $cart =  $cart->where('product_variation_id', $data['product_variation_id'])->first();
            }

            if ($cart) {
                $cart->quantity += $data['quantity'];
                $cart->save();
                return $cart;
            } else {
                return $this->cartRepository->create($data);
            }
        } catch (\Exception $e) {
            throw new \Exception(__('messages.cart.error-cart-add'));
        }
    }
    function update(int|string $id, array $data, array $option = [])
    {

        $cart = $this->cartRepository->find($id);
        if ($cart->product_variation_id) {
            $quantity = $cart->product_variation->stock_qty;
            if ($quantity < $data['quantity']) {
                $cart->quantity = $quantity;
                $cart->save();
                throw new \Exception(__('messages.cart.error-quantity'));
            }
        } else {
            $quantity = $cart->product->stock_qty;
            if ($quantity < $data['quantity']) {
                $cart->quantity = $quantity;
                $cart->save();
                throw new \Exception(__('messages.cart.error-quantity'));
            }
        }
        $cart->quantity = $data['quantity'];
        $cart->save();
        return $cart;
    }
    function delete(int|string $id)
    {
        try {
            $cart = $this->cartRepository->find($id);
            if ($cart) $cart->delete($id);
            else  throw new \Exception(__('messages.error-not-found'));
        } catch (\Exception $e) {
            throw new \Exception(__('messages.cart.error-delete-cart'));
        }
    }
}

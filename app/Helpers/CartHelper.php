<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartHelper
{
    /**
     * Get current user's cart
     */
    public static function getCart()
    {
        if (!Auth::check()) {
            return null;
        }

        $cart = Order::cart(Auth::id())->with('details.product')->first();

        if (!$cart) {
            $cart = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'shipping_cost' => 0,
                'grand_total' => 0,
                'status' => 'in_cart'
            ]);
        }

        return $cart;
    }

    /**
     * Get cart total items
     */
    public static function getCartItemCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        $cart = self::getCart();

        if (!$cart) {
            return 0;
        }

        return OrderDetail::where('order_id', $cart->id)->sum('quantity');
    }

    /**
     * Add product to cart
     */
    public static function addToCart($productId, $quantity = 1)
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'message' => 'Please login to add products to cart'
            ];
        }

        $product = Product::find($productId);

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Product not found'
            ];
        }

        DB::beginTransaction();

        try {
            // Get or create cart
            $cart = self::getCart();

            // Check if product already in cart
            $cartItem = OrderDetail::where('order_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                // Update quantity
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $quantity,
                    'subtotal' => $cartItem->price * ($cartItem->quantity + $quantity)
                ]);
            } else {
                // Add new product to cart
                OrderDetail::create([
                    'order_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $quantity
                ]);
            }

            // Update cart total
            self::updateCartTotal($cart);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Product added to cart',
                'cart' => $cart->fresh(['details.product'])
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update cart total helper
     */
    public static function updateCartTotal($cart)
    {
        $totalAmount = OrderDetail::where('order_id', $cart->id)->sum('subtotal');

        $cart->update([
            'total_amount' => $totalAmount,
            'grand_total' => $totalAmount + $cart->shipping_cost
        ]);

        return $cart;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Tampilkan halaman cart
     */
    public function index()
    {
        $title = 'Cart';
        $user = Auth::user();
        $cart = Order::cart($user->id)->with('details.product')->first();

        if (!$cart) {
            $cart = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0,
                'shipping_cost' => 0,
                'grand_total' => 0,
                'status' => 'in_cart'
            ]);
        }

        return view('pages.orders.cart', compact('title', 'cart'));
    }

    /**
     * Tambahkan produk ke cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        DB::beginTransaction();

        try {
            // Get or create cart
            $cart = Order::cart($user->id)->first();

            if (!$cart) {
                $cart = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => 0,
                    'shipping_cost' => 0,
                    'grand_total' => 0,
                    'status' => 'in_cart'
                ]);
            }

            // Check if product already in cart
            $cartItem = OrderDetail::where('order_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                // Update quantity if product already in cart
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $request->quantity,
                    'subtotal' => $cartItem->price * ($cartItem->quantity + $request->quantity)
                ]);
            } else {
                // Add new product to cart
                OrderDetail::create([
                    'order_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $request->quantity
                ]);
            }

            // Update cart total
            $this->updateCartTotal($cart);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Product added to cart!',
                    'cart' => $cart->load('details.product')
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to add product to cart: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to add product to cart: ' . $e->getMessage());
        }
    }

    /**
     * Update quantity di cart
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_details,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $cart = Order::cart($user->id)->first();

        if (!$cart) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Cart not found!'
                ], 404);
            }

            return redirect()->route('cart.index')->with('error', 'Cart not found!');
        }

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                $cartItem = OrderDetail::findOrFail($item['id']);

                // Ensure the cart item belongs to the user's cart
                if ($cartItem->order_id !== $cart->id) {
                    continue;
                }

                // Get product and check stock availability
                $product = $cartItem->product;
                if (!$product) {
                    DB::rollBack();

                    if ($request->wantsJson()) {
                        return response()->json([
                            'error' => 'Produk tidak ditemukan!'
                        ], 404);
                    }

                    return back()->with('error', 'Produk tidak ditemukan!');
                }

                // Validasi quantity tidak melebihi stok
                if ($item['quantity'] > $product->stock) {
                    DB::rollBack();

                    $errorMessage = "Mohon maaf, stok untuk produk \"{$product->name}\" saat ini tidak mencukupi. Tersedia: {$product->stock}, sedangkan jumlah yang diminta sebanyak: {$item['quantity']}.";


                    if ($request->wantsJson()) {
                        return response()->json([
                            'error' => $errorMessage,
                            'product_name' => $product->name,
                            'available_stock' => $product->stock,
                            'requested_quantity' => $item['quantity']
                        ], 400);
                    }

                    return back()->with('error', $errorMessage);
                }

                $cartItem->update([
                    'quantity' => $item['quantity'],
                    'subtotal' => $cartItem->price * $item['quantity']
                ]);
            }

            // Update cart total
            $this->updateCartTotal($cart);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Keranjang berhasil diperbarui!',
                    'cart' => $cart->load('details.product')
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Gagal memperbarui keranjang: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui keranjang: ' . $e->getMessage());
        }
    }

    /**
     * Hapus item dari cart
     */
    public function removeItem($id)
    {
        $user = Auth::user();
        $cart = Order::cart($user->id)->first();

        if (!$cart) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Cart not found!'
                ], 404);
            }

            return redirect()->route('cart.index')->with('error', 'Cart not found!');
        }

        $cartItem = OrderDetail::findOrFail($id);

        // Ensure the cart item belongs to the user's cart
        if ($cartItem->order_id !== $cart->id) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Item not found in your cart!'
                ], 404);
            }

            return redirect()->route('cart.index')->with('error', 'Item not found in your cart!');
        }

        DB::beginTransaction();

        try {
            $cartItem->delete();

            // Update cart total
            $this->updateCartTotal($cart);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Item removed from cart!',
                    'cart' => $cart->load('details.product')
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to remove item: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to remove item: ' . $e->getMessage());
        }
    }

    /**
     * Helper method untuk update total cart
     */
    protected function updateCartTotal(Order $cart)
    {
        $totalAmount = OrderDetail::where('order_id', $cart->id)->sum('subtotal');

        $cart->update([
            'total_amount' => $totalAmount,
            'grand_total' => $totalAmount + $cart->shipping_cost
        ]);
    }
}

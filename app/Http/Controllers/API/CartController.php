<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (auth('sanctum')->check()) {

            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;

            // 🚩 Change 'product_qty' to 'product_quantity' to match your React code
            $product_qty = $request->product_quantity;

            $productCheck = Product::where('id', $product_id)->first();

            if ($productCheck) {
                if (Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'Product already in cart',
                    ]);
                } else {
                    $cartItem = new Cart();
                    $cartItem->user_id = $user_id;
                    $cartItem->product_id = $product_id;

                    // ✅ Assign the variable we got above to the DB column 'product_qty'
                    $cartItem->product_qty = $product_qty;
                    $cartItem->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Product added to cart successfully',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    public function viewCart()
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('user_id', $user_id)->with('product')->get();

            return response()->json([
                'status' => 200,
                'cart' => $cartItems,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to view cart',
            ]);
        }
    }

    public function updateCartQuantity(Request $request, $cart_id, $scope)
    {
        if (auth('sanctum')->check()) {

            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            // Check if cart item exists
            if ($scope === 'increment') {
                $cartItem->product_qty += 1;
            } elseif ($scope === 'decrement') {
                $cartItem->product_qty -= 1;
            }
            $cartItem->update();
            // return response()->json([
            //     'status' => 200,
            //     'message' => 'Cart quantity updated successfully',
            // ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

    public function deleteCartItem($cart_id)
    {
        if (auth('sanctum')->check()) {

            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($cartItem) {
                $cartItem->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Cart item Removed successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cart item not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }
}

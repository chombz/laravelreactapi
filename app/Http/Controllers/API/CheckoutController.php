<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Cart;



class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'firstname' => 'required|string|max:191',
                    'lastname' => 'required|string|max:191',
                    'phone' => 'required|string|max:191',
                    'email' => 'required|string|max:191',
                    'address' => 'required|string|max:500',
                    'city' => 'required|string|max:191',
                    'state' => 'required|string|max:191',
                ]
            );

            // Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ]);
            } else {

                /*----------------------------------------------------Create the order record-------------------------------------------------*/
                $user_id = auth('sanctum')->user()->id;
                $order = new Order();
                $order->user_id = auth('sanctum')->user()->id;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->phone = $request->phone;
                $order->email = $request->email;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->remark = "None";

                $order->payment_mode = $request->payment_mode;
                $order->payment_id = $request->payment_id;
                $order->tracking_no = 'chombzecom' . rand(1111, 9999);
                $order->save();

                $cart = Cart::where('user_id', $user_id)->get();

                // Create order items and reduce product stock
                $orderitems = [];
                foreach ($cart as $item) {
                    $orderitems[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->product_qty,
                        'price' => $item->product->selling_price,
                    ];

                    // Reduce the product stock
                    $item->product->update([
                        'quantity' => $item->product->quantity - $item->product_qty,
                    ]);
                }


                $order->orderItems()->createMany($orderitems); //Save order items
                Cart::destroy($cart->pluck('id')); //delete cart

                /*----------------------------------------------------------------------------------------------------------------------------*/

                return response()->json([
                    'status' => 200,
                    'message' => 'Order placed successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }


    //
    public function validateOrder(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'firstname' => 'required|string|max:191',
                    'lastname' => 'required|string|max:191',
                    'phone' => 'required|string|max:191',
                    'email' => 'required|string|max:191',
                    'address' => 'required|string|max:500',
                    'city' => 'required|string|max:191',
                    'state' => 'required|string|max:191',
                ]
            );

            // Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Validation successfully',
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

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with(['orderItems.product'])->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 200,
                'orders' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching orders: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['orderItems.product'])->find($id);

            if ($order) {
                return response()->json([
                    'status' => 200,
                    'order' => $order,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Order not found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching order: ' . $e->getMessage(),
            ], 500);
        }
    }
}

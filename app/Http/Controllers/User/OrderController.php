<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Check if the user is authenticated
        if ($user) {
            // Retrieve orders for the authenticated user
            $orders = $user->orders()->with('order_items')->get();
            // Check if orders were found
            if ($orders->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "orders" => $orders,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "orders" => "No orders found for this user.",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }
    }

    public function show($id)
    {
        $order = Order::with('order_items')->find($id);
        if (!$order) {
            return response()->json([
                "status" => 404,
                "message" => "Order not found"
            ], 404);
        }

        // Ensure the authenticated user can access this order
        if (Auth::user()->id !== $order->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "Unauthorized to access this order"
            ], 403);
        }

        return response()->json([
            "status" => 200,
            "order" => $order
        ], 200);
    }

    public function store(StoreOrderRequest $request)
    {
        // Ensure the authenticated user is creating their own order
        if (Auth::user()->id !== $request->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "Unauthorized to create this order"
            ], 403);
        }

        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $order = Order::create($data);

        if ($order) {
            return response()->json([
                "status" => 200,
                "order" => $order,
                "message" => "Order created successfully"
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong"
            ], 500);
        }
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $order = Order::findOrFail($id);

        // Ensure the authenticated user can update this order
        if (Auth::user()->id !== $order->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "Unauthorized to update this order"
            ], 403);
        }

        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        try {
            $order->update($data);

            return response()->json([
                'order' => $order,
                'message' => 'Order updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Order: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                "status" => 404,
                "message" => "Order not found"
            ], 404);
        }

        // Ensure the authenticated user can delete this order
        if (Auth::user()->id !== $order->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "Unauthorized to delete this order"
            ], 403);
        }

        $order->delete();

        return response()->json([
            "status" => 200,
            "message" => "Order Deleted Successfully"
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $purchase_order = $user->purchase_orders;

            return response()->json([
                "status" => 200,
                "purchase_order" => $purchase_order,
            ], 200);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user) {
            $purchase_order = $user->purchase_orders()->find($id);

            if ($purchase_order) {
                return response()->json([
                    "status" => 200,
                    "purchase_order" => $purchase_order,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Purchase Order not found",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if ($user) {
            $purchase_order = $user->purchase_orders()->create($data);

            return response()->json([
                "status" => 200,
                "purchase_order" => $purchase_order,
                "message" => "Purchase Order created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function update(UpdatePurchaseOrderRequest $request, $id)
    {
        $data = $request->validated();
        $user = Auth::user();

        if ($user) {
            $purchase_order = $user->purchase_orders()->find($id);

            if ($purchase_order) {
                $purchase_order->update($data);

                return response()->json([
                    'purchase_order' => $purchase_order,
                    'message' => 'Purchase Order updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Purchase Order not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user) {
            $purchase_order = $user->purchase_orders()->find($id);

            if ($purchase_order) {
                $purchase_order->delete();
                return response()->json([
                    "status" => 200,
                    "message" => "Purchase Order Deleted Successfully",
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Purchase Order not found",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }
    public function showByBranchID($id)
    {
        $user = auth()->user();

        // Get the authenticated user
        // Check if the user owns the branch
        $branch = $user->branches()->find($id);
        if ($branch) {
            // Fetch inventories associated with the branch
            $purchase_orders = $branch->purchase_orders;
            // Check if $purchase_order is not null before calling count()
            if ($purchase_orders !== null && $purchase_orders->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "purchase_order" => $purchase_orders,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "No Purchase Order found for this branch",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 403,
                "message" => "You are not authorized to view Purchase Order for this branch",
            ], 403);
        }
    }

}

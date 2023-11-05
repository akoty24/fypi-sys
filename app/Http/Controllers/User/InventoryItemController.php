<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryItem\StoreInventoryItemRequest;
use App\Http\Requests\InventoryItem\UpdateInventoryItemRequest;
use App\Models\Inventory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InventoryItemController extends Controller
{
    public function subscribe_inventory(){

        $user = Auth::user();
        $inventory = Inventory::create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Inventory added successfully',
            'inventory' => $inventory,
        ], 201);
    }

    public function index()
    {
        // Assuming the user is authenticated
        $user = Auth::user();

        if ($user) {
            // Fetch inventories associated with the user
            $inventories = $user->inventories()->with('inventoryItems')->get();

            if ($inventories->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "inventories" => $inventories,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "inventories" => "No inventories found for this user",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }
    public function show($inventory_item_id)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            // Find the inventory item based on the given $inventory_item_id
            $inventoryItem = InventoryItem::find($inventory_item_id);

            if ($inventoryItem) {
                // Retrieve the associated inventory for this inventory item
                $inventory = $inventoryItem->inventory;

                // Check if the inventory belongs to the authenticated user
                if ($inventory && $inventory->user_id === $user->id) {
                    // Retrieve the inventory items associated with this inventory
                    $inventoryItems = $inventory->inventoryItems;

                    return response()->json([
                        "status" => 200,
                        "inventory_items" => $inventoryItems
                    ], 200);
                } else {
                    return response()->json([
                        "status" => 404,
                        "message" => "Inventory not found for this user"
                    ], 404);
                }
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Inventory item not found"
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized"
            ], 401);
        }
    }
    public function showByInventoryID($id)
    {
        $user = auth()->user(); // Get the authenticated user

        // Check if the user owns the specified inventory
        $inventory = $user->inventories()->find($id);

        if ($inventory) {
            // Fetch inventory items associated with the inventory
            $inventoryItems = $inventory->inventoryItems;

            if ($inventoryItems->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "inventory_items" => $inventoryItems,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "No inventory items found for this inventory",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 403,
                "message" => "You are not authorized to view inventory items for this inventory",
            ], 403);
        }
    }

    public function store(StoreInventoryItemRequest $request)
    {
        $data = $request->validated();

        $validator = Validator::make($data, (new StoreInventoryItemRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }

        // Get the authenticated user
        $user = auth()->user();

        // Check if the inventory ID is provided in the request
        if ($request->has('inventory_id')) {
            $id = $request->inventory_id;
        } else {
            // If not provided, use the first inventory associated with the user
            $id = $user->inventories->first()->id;
        }

        // Check if the inventory belongs to the authenticated user
        $inventory = $user->inventories()->find($id);

        if ($inventory) {
            $data['inventory_id'] = $inventory->id;
            $data['user_id'] = $user->id;

            // Create the inventory item
            $inventory_item = InventoryItem::create($data);

            if ($inventory_item && $request->has('branches')) {
                // Attach branches to the inventory item
                $inventory_item->branches()->attach($request->branches);
            }

            return response()->json([
                "status" => 200,
                "inventory_item" => $inventory_item,
                "message" => "Inventory item created successfully",
            ], 200);
        }

        return response()->json([
            "status" => 500,
            "error" => "Something went wrong",
        ], 500);
    }


    public function update(UpdateInventoryItemRequest $request, $id)
    {
        $inventoryItem = InventoryItem::find($id);

        if (!$inventoryItem) {
            return response()->json([
                "status" => 404,
                "message" => "Inventory item not found",
            ], 404);
        }

        // Check if the inventory item belongs to the authenticated user's inventory
        $user = auth()->user();
        if ($inventoryItem->inventory->user_id !== $user->id) {
            return response()->json([
                "status" => 403,
                "message" => "You are not authorized to update this inventory item",
            ], 403);
        }

        $data = $request->validated();
        $inventoryItem->update($data);

        return response()->json([
            "status" => 200,
            "inventory_item" => $inventoryItem,
            "message" => "Inventory item updated successfully",
        ], 200);
    }
    public function destroy($id)
    {
        $inventoryItem = InventoryItem::find($id);

        if (!$inventoryItem) {
            return response()->json([
                "status" => 404,
                "message" => "Inventory item not found",
            ], 404);
        }

        // Check if the inventory item belongs to the authenticated user's inventory
        $user = auth()->user();
        if ($inventoryItem->inventory->user_id !== $user->id) {
            return response()->json([
                "status" => 403,
                "message" => "You are not authorized to delete this inventory item",
            ], 403);
        }

        $inventoryItem->delete();

        return response()->json([
            "status" => 200,
            "message" => "Inventory item deleted successfully",
        ], 200);
    }

}


<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryRequest;
use App\Http\Requests\Inventory\UpdateInventoryRequest;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class InventoryController extends Controller
{
    public function index()
    {
        // Assuming the user is authenticated
        $user = Auth::guard('web')->user();

        if ($user) {
            // Fetch inventories associated with the user
            $inventories = $user->inventories;

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



    public function show($id)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            // Fetch the category associated with the user by the given $id
            $inventory = $user->inventories()->find($id);

            if ($inventory) {
                return response()->json([
                    "status" => 200,
                    "inventory" => $inventory
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "inventory not found for this user"
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized"
            ], 401);
        }
    }
    public function showByBranchID($id)
    {
        $user = auth()->user(); // Get the authenticated user
        // Check if the user owns the branch
        $branch = $user->branches()->find($id);
        if ($branch) {
            // Fetch inventories associated with the branch
            $inventories = $branch->inventories;
            if ($inventories->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "inventories" => $inventories,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "No inventories found for this branch",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 403,
                "message" => "You are not authorized to view inventories for this branch",
            ], 403);
        }
    }



    public function store(StoreInventoryRequest  $request)
    {
        $data = $request->validated();

        $validator = Validator::make($data, (new StoreInventoryRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }
        if ($request->branch_id) {
            $data['branch_id'] = $request->branch_id;
        } else {
            $data['branch_id'] = null;
        }
        $data['user_id'] = Auth::id();
        $inventory = Inventory::create($data);

        if ($inventory) {
            return response()->json([
                "status" => 200,
                "inventory" => $inventory,
                "message" => "Inventory created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong",
            ], 500);
        }
    }
    public function update(UpdateInventoryRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->branch_id) {
            $data['branch_id'] = $request->branch_id;
        } else {
            $data['branch_id'] = null;
        }
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['error' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }

        // Validate the request using the UpdateInventoryRequest

        try {
            $inventory->update($data);
            return response()->json(['inventory' => $inventory, 'message' => 'inventory updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update inventory: ' . $e->getMessage()], 500);
        }  }

    public function destroy($id)
    {

        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['error' => 'Inventory not found'], Response::HTTP_NOT_FOUND);
        }
        // Delete the inventory
        $inventory->delete();

        return response()->json(['message' => 'Inventory deleted successfully'], Response::HTTP_OK);
    }
}

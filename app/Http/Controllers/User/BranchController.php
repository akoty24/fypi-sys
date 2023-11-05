<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user's store and its branches
        $user = Auth::guard('web')->user();
        if ($user) {
            $store = $user->stores->first();
            if ($store) {
                $branches = $store->branches;
                if ($branches->count() > 0) {
                    return response()->json([
                        "status" => 200,
                        "branches" => $branches,
                    ], 200);
                } else {
                    return response()->json([
                        "status" => 404,
                        "branches" => "No branches found for this store",
                    ], 404);
                }
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "No store found for this user",
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

        // Assuming you have a Store model and a branches relationship in the Store model
        $store = $user->stores()->first();  // Retrieve the first store of the user

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }

        // Check if the branch belongs to the store
        $branch = $store->branches()->find($id);

        if (!$branch) {
            return response()->json([
                "status" => 404,
                "message" => "Branch not found for this store.",
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "branch" => $branch,  // Include the branch details in the response
        ], 200);
    }

    public function store(StoreBranchRequest $request)
    {
        $data = $request->validated();

        $validator = Validator::make($data, (new StoreBranchRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }

        $user = Auth::guard('web')->user();

        // Assuming you have a Store model and a branches relationship in the Store model
        $store = $user->stores()->first();  // Retrieve the first store of the user

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }

        // Create the branch
        $branch = $store->branches()->create($data);

        return response()->json([
            "status" => 200,
            "message" => "Branch created and associated with the store successfully",
            "branch" => $branch  // Include the created branch in the response
        ], 200);
    }

    public function update(UpdateBranchRequest $request, $id)
    {
        $data = $request->validated();

        $user = Auth::guard('web')->user();

        // Assuming you have a Store model and a branches relationship in the Store model
        $store = $user->stores()->first();  // Retrieve the first store of the user

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }

        // Check if the branch belongs to the store
        $branch = $store->branches()->find($id);

        if (!$branch) {
            return response()->json([
                "status" => 404,
                "message" => "Branch not found for this store.",
            ], 404);
        }

        // Update the branch
        $branch->update($data);

        return response()->json([
            "status" => 200,
            "message" => "Branch updated successfully",
            "branch" => $branch  // Include the updated branch in the response
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::guard('web')->user();

        // Assuming you have a Store model and a branches relationship in the Store model
        $store = $user->stores()->first();  // Retrieve the first store of the user

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }

        // Check if the branch belongs to the store
        $branch = $store->branches()->find($id);

        if (!$branch) {
            return response()->json([
                "status" => 404,
                "message" => "Branch not found for this store.",
            ], 404);
        }

        // Delete the branch
        $branch->delete();

        return response()->json([
            "status" => 200,
            "message" => "Branch deleted successfully",
        ], 200);
    }
}

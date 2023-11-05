<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Inventory\StoreInventoryRequest;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Inventory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        // Check if the user is authenticated
        if ($user) {
            $categories = $user->categories()->get();

            if ($categories->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "categories" => $categories,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "categories" => "No categories found for this user",
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
        // Assuming the user is authenticated
        $user = Auth::guard('web')->user();

        if ($user) {
            // Fetch the category associated with the user by the given $id
            $category = $user->categories()->find($id);

            if ($category) {
                return response()->json([
                    "status" => 200,
                    "category" => $category
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Category not found for this user"
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
            // Fetch categories associated with the branch
            $categories = $branch->categories;

            if ($categories->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "categories" => $categories,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "No categories found for this branch",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 403,
                "message" => "You are not authorized to view categories for this branch",
            ], 403);
        }
    }


    public function store(StoreCategoryRequest  $request)
    {

        $data = $request->validated();

        $validator = Validator::make($data, (new StoreCategoryRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/category');
        } else {
            $data['photo'] = null;
        }
        if ($request->branch_id) {
            $data['branch_id'] = $request->branch_id;
        } else {
            $data['branch_id'] = null;
        }
        $data['user_id'] = Auth::id();
        $category = Category::create($data);

        if ($category) {
            return response()->json([
                "status" => 200,
                "category" => $category,
                "message" => "Category created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong",
            ], 500);
        }
          }
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('photo')) {
            Storage::delete($category->photo); // Delete old photo
            $photoPath = $request->file('photo')->store('photos/category');
            $data['photo'] = $photoPath;
        }
        try {
            $category->update($data);
            return response()->json(['category' => $category, 'message' => 'Category updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Category: ' . $e->getMessage()], 500);
        }

    }
    public function destroy($id)
    {
        // Ensure the request is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        // Delete the category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
}

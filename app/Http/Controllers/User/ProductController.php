<?php

namespace App\Http\Controllers\User;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Product\StoreProductRequest;
    use App\Http\Requests\Product\UpdateProductRequest;
    use App\Models\Branch;
    use App\Models\Category;
    use App\Models\Product;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // Assuming the user is authenticated
        $user = Auth::user();
        // Check if the user is authenticated
        if ($user) {
            // Fetch categories associated with the user
            $categories = $user->categories;

            $products = [];

            // Loop through each category and fetch its products
            foreach ($categories as $category) {
                $products = array_merge($products, $category->products->toArray());
            }

            if (!empty($products)) {
                return response()->json([
                    "status" => 200,
                    "products" => $products,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "products" => "No products found for this user",
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
            // Fetch the product associated with the user and the given $id
            $product = Product::whereHas('category', function ($query) use ($user, $id) {
                $query->where('user_id', $user->id);
            })->find($id);

            if ($product) {
                return response()->json([
                    "status" => 200,
                    "product" => $product
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Product not found for this user"
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized"
            ], 401);
        }
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/product');
        } else {
            $data['photo'] = null;
        }

        $product = Product::create($data);

        if ($product) {
            return response()->json([
                "status" => 200,
                "product" => $product,  // Include the created branch in the response
                "message" => "Product created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong",
            ], 500);
        }
        

    }
    public function update(UpdateProductRequest $request, $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Handle file upload if a new photo is provided
        if ($request->hasFile('photo')) {
            // Delete old photo
            Storage::delete($product->photo);
            // Upload new photo
            $photoPath = $request->file('photo')->store('photos/product');
        } else {
            $photoPath = $product->photo;
        }

        // Update the product details
        $product->update([
            'name_ar' => $request->input('name_ar'),
            'name_en' => $request->input('name_en'),
            'photo' => $photoPath,
            'is_retail' => $request->input('is_retail'),
            'product_code' => $request->input('product_code'),
            'pricing_method' => $request->input('pricing_method'),
            'price' => $request->input('price'),
            'tax_group' => $request->input('tax_group'),
            'cost_calculation_method' => $request->input('cost_calculation_method'),
            'cost' => $request->input('cost'),
            'selling_unit' => $request->input('selling_unit'),
            'category_id' => $request->input('category_id'),
        ]);

        return response()->json(['product' => $product, 'message' => 'Product updated successfully'], 200);
    }
    public function destroy($id)
    {
        // Ensure the request is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        Storage::delete($product->photo); // Delete the product's photo
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);

    }
    public function showByCategoryID($id){
        // Get the authenticated user
        $user = Auth::user();

        // Find the category for the given category_id associated with the authenticated user
        $category = $user->categories()->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found or does not belong to the authenticated user'], 404);
        }

        $products = $category->products;

        return response()->json(['products' => $products], 200);
    }

    public function showByBranchID($id)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            // Check if the given branch belongs to the user
            $branch = $user->branches()->find($id);

            if ($branch) {
                // Fetch categories associated with the branch
                $categories = $branch->categories;

                $products = [];

                // Loop through each category and fetch its products
                foreach ($categories as $category) {
                    $products = array_merge($products, $category->products->toArray());
                }

                if (!empty($products)) {
                    return response()->json([
                        "status" => 200,
                        "products" => $products,
                    ], 200);
                } else {
                    return response()->json([
                        "status" => 404,
                        "products" => "No products found for this branch",
                    ], 404);
                }
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Branch not found for this user",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }


}

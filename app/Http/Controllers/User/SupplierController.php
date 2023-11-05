<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $suppliers = $user->suppliers;

            return response()->json([
                "status" => 200,
                "suppliers" => $suppliers,
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
            $supplier = $user->suppliers()->find($id);

            if ($supplier) {
                return response()->json([
                    "status" => 200,
                    "supplier" => $supplier,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Supplier not found",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if ($user) {
            $supplier = $user->suppliers()->create($data);

            return response()->json([
                "status" => 200,
                "supplier" => $supplier,
                "message" => "Supplier created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 401,
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        $data = $request->validated();
        $user = Auth::user();

        if ($user) {
            $supplier = $user->suppliers()->find($id);

            if ($supplier) {
                $supplier->update($data);

                return response()->json([
                    'supplier' => $supplier,
                    'message' => 'Supplier updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Supplier not found',
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
            $supplier = $user->suppliers()->find($id);

            if ($supplier) {
                $supplier->delete();
                return response()->json([
                    "status" => 200,
                    "message" => "Supplier Deleted Successfully",
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "message" => "Supplier not found",
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

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreRequest;
use App\Http\Requests\Store\UpdateRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class StoreController extends Controller
{

    public function store(StoreRequest $request)
    {

        //limit the user to create only one store
        $user = auth()->user();
        $store = $user->stores->first();
        if ($store) {
            return response()->json([
                'status' => 422,
                'message' => 'You already have a store',
            ], 422);
        }

        $data = $request->validated();
        $validator = Validator::make($data, (new StoreRequest())->rules());
        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }

        // Create the store first
        $store = Store::create($data);

        if ($store) {
            // Attach the user to the store in the 'store_users' pivot table
            auth()->user()->stores()->attach($store->id);
            //create a branch for the store
            $branch = $store->branches()->create([
                "name_ar" => "الرئيسي",
                "name_en" => "Main Branch",
                "reference_number" => "12345755",
                "tax_group" => "VAT",
                "branch_tax" => "0.15",
                "branch_tax_number" => "TAX987654",
                "beginning_work" => "08:00 AM",
                "end_work" => "08:00 BM",
                "end_day_inventory" => "100",
                "phone" => "01027401686",
                "address" => "15 Sellamuttu Avenue, 03",
                "street_name" => "Main St",
                "building_number" => "1234",
                "extension_number" => "101",
                "city" => "Sample City",
                "district" => "Sample District",
                "postal_code" => "123456",
                "commercial_registration_number" => "CRN876543",
                "latitude" => "40.712776",
                "longitude" => "74.005974",
                "order_viewer_application" => "OrderViewerApp",
                "top_of_invoice" => "Thank you for your order",
                "bottom_of_invoice" => "Please visit again",
                "status" => "1",
                "store_id" => $store->id,
            ]);
            return response()->json([
                "status" => 200,
                "store" => $store,
                "message" => "Store created and associated successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong",
            ], 500);
        }
    }

    public function show()
    {
        $user = auth()->user();
        $store = $user->stores;
        if (!$store) {
            return response()->json([
                'status' => 404,
                'message' => 'Store information not found for this user.',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'store' => $store,
        ], 200);
    }

//    public function update(UpdateRequest $request)
//    {
//
//        $validatedData = $request->validated();
//
//        $user = auth()->user();
//        $store = $user->stores;
//
//        if (!$store) {
//            return response()->json([
//                'status' => 404,
//                'message' => 'Store information not found for this user.',
//            ], 404);
//        }
//        // Update the store details
//        $store->update($validatedData);
//
//        return response()->json([
//            'status' => 200,
//            'message' => 'Store information updated successfully',
//        ], 200);
//    }
    public function update(UpdateRequest $request)
    {
        $validatedData = $request->validated();

        $user = auth()->user();
        $store = $user->stores;

        if (!$store) {
            return response()->json([
                'status' => 404,
                'message' => 'Store information not found for this user.',
            ], 404);
        }
        // Update the store details
        $store->update($validatedData);

        return response()->json([
            'status' => 200,
            'message' => 'Store information updated successfully',
        ], 200);
    }

    public function destroy()
    {
        $user = auth()->user();
        $store = $user->stores;

        if (!$store) {
            return response()->json([
                'status' => 404,
                'message' => 'Store not found for this user.',
            ], 404);
        }

        // Delete the store
        $store->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Store deleted successfully',
        ], 200);
    }

}
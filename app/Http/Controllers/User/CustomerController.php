<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }
        // Retrieve the store associated with the authenticated user
        $store = $user->stores->first();
        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "No store found for this user.",
            ], 404);
        }
        // Get all customers associated with the store
        $customers = $store->customers;

        if ($customers->isEmpty()) {
            return response()->json([
                "status" => 404,
                "message" => "No customers found for this store.",
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "customers" => $customers,
        ], 200);
    }


    public function show($id)
    {
        $customer = Customer::with('customer_points')->find($id);
        if (!$customer) {
            return response()->json([
                "status" => 404,
                "message" => "Customer not found"
            ], 404);
        }

        // Ensure the authenticated user can access this customer
        if (Auth::user()->id !== $customer->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "Unauthorized to access this customer"
            ], 403);
        }

        return response()->json([
            "status" => 200,
            "customer" => $customer
        ], 200);
    }

    public function store(StoreCustomerRequest $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Retrieve the store associated with the authenticated user
        $store = $user->stores->first();
        // Check if a store is associated with the user
        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "No store found for this user.",
            ], 404);
        }

        // Get the validated data from the request
        $validatedData = $request->validated();

        // Create a new customer for the store using the validated data
        $customer = $store->customers()->create($validatedData);

        return response()->json([
            "status" => 200,
            "message" => "Customer created successfully.",
            "customer" => $customer,
        ], 200);
    }


    public function update(UpdateCustomerRequest $request, $id)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Retrieve the customer associated with the store and user
        $customer = Customer::where('store_id', $user->stores->first()->id)
            ->where('id', $id)
            ->first();

        // Check if the customer exists
        if (!$customer) {
            return response()->json([
                "status" => 404,
                "message" => "Customer not found.",
            ], 404);
        }

        // Get the validated data from the request
        $validatedData = $request->validated();

        // Update the customer with the validated data
        $customer->update($validatedData);

        return response()->json([
            "status" => 200,
            "message" => "Customer updated successfully.",
            "customer" => $customer,
        ], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                "status" => 404,
                "message" => "Customer not found"
            ], 404);
        }

        // Ensure the authenticated user can delete this customer
        if (Auth::user()->id !== $customer->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "Unauthorized to delete this customer"
            ], 403);
        }

        $customer->delete();

        return response()->json([
            "status" => 200,
            "message" => "Customer Deleted Successfully"
        ], 200);
    }
}

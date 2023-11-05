<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
            $customers =Customer::all();
            if ($customers->count() > 0) {
                return response()->json([
                    "status" => 200,
                    "customers" => $customers,
                ], 200);
            } else {
                return response()->json([
                    "status" => 404,
                    "customers" => "No customers found ",
                ], 404);
            }
        }


    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json([
                "status" => 404,
                "message" => "Customer not found"
            ], 404);
        }
        return response()->json([
            "status" => 200,
            "customer" => $customer
        ], 200);
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $customer = Customer::create($data);

        if ($customer) {
            return response()->json([
                "status" => 200,
                "customer" => $customer,
                "message" => "Customer created successfully"
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong"
            ], 500);
        }
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        try {
            $customer->update($data);

            return response()->json([
                'customer' => $customer,
                'message' => 'Customer updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Customer: ' . $e->getMessage()], 500);
        }
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
        $customer->delete();
        return response()->json([
            "status" => 200,
            "message" => "Customer Deleted Successfully"
        ], 200);
    }
}
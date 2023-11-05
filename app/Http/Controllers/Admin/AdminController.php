<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        if ($admins->count()>0){

            return response()->json([
                "status"=>200,
                "admins"=>$admins,
            ],200);
        }
        else{
            return response()->json([
                "status"=>404,
                "admins"=>"no admins found",
            ],404);
        }
    }
    public function show($id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            return response()->json([
                "status"=>200,
                "admin" => $admin
            ], 200);
        }else{
            return response()->json([
                "status"=>404,
                "message" => "Admin not found"
            ], 404);
        }
    }
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();

        $validator = Validator::make($data, (new StoreAdminRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }

        $data['password'] = bcrypt($data['password']);  // Hash the password

        $admin = Admin::create($data);

        if ($admin) {
            return response()->json([
                "status" => 200,
                "message" => "Admin created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong",
            ], 500);
        }
    }
    public function update(UpdateAdminRequest $request, $id)
    {
        // Validate the request
        $data = $request->validated();

        // Find the user by ID
        $admin = Admin::findOrFail($id);

        try {
            $data['password'] = bcrypt($data['password']);  // Hash the password
            $admin->update($data);

            return response()->json([
                'admin' => $admin,
                'message' => 'Admin updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Admin: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            $admin->delete();
            return response()->json([
                "status"=>200,
                "message"=>"Admin Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                "status"=>404,
                "message"=>"Admin not found",
            ],404);
        }

    }
}
////        try {
////            // Start a database transaction
////            DB::beginTransaction();
////
////            $admin = Admin::find($id);
////
////            if (!$admin) {
////                return response()->json(['message' => 'Admin not found'], 404);
////            }
////
////            $validator = Validator::make($request->all(), (new UpdateAdminRequest())->rules());
////
////            if ($validator->fails()) {
////
////                return response(0,$validator->errors()->first()??"error",null );
////            }
////
////            // Hash the password if provided
////            if (isset($data['password'])) {
////                $data['password'] = bcrypt($data['password']);
////            }
////
////            $updateSuccess = $admin->update($data);
////
////            if ($updateSuccess) {
////                // Commit the transaction if the update was successful
////                DB::commit();
////                return response()->json(['admin' => $admin, 'message' => 'Admin updated successfully'], 200);
////            } else {
////                // If the update was not successful, rollback the transaction
////                DB::rollBack();
////                return response()->json(['message' => 'Admin update failed'], 500);
////            }
////        } catch (\Exception $e) {
////            // An error occurred, rollback the transaction
////            DB::rollBack();
////
////            return response()->json(['message' => 'An error occurred during the update.'], 500);
////        }
//    }
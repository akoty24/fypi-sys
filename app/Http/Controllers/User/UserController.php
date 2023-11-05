<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('store')->get();
        if ($users->count() > 0) {
            return response()->json([
                "status" => 200,
                "users" => $users,
            ], 200);
        } else {
            return response()->json([
                "status" => 404,
                "users" => "No users found",
            ], 404);
        }
    }

    public function show($id)
    {
        $user = User::with('store')->find($id);

        if ($user) {
            return response()->json([
                "status" => 200,
                "user" => $user,
            ], 200);
        } else {
            return response()->json([
                "status" => 404,
                "message" => "User not found",
            ], 404);
        }
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $validator = Validator::make($data, (new StoreUserRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                "errors" => $validator->errors(),
            ], 422);
        }

        $data['password'] = bcrypt($data['password']);  // Hash the password

        $user = User::create($data);

        if ($user) {
            return response()->json([
                "status" => 200,
                "message" => "User created successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                "error" => "Something went wrong",
            ], 500);
        }
    }
    public function update(UpdateUserRequest $request, $id)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Validate the request
        $data = $request->validated();

        // Find the user by ID
        $user = User::findOrFail($id);

        try {
            $data['password'] = bcrypt($data['password']);  // Hash the password
            $user->update($data);

            return response()->json([
                'user' => $user,
                'message' => 'User updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json([
                "status"=>200,
                "message"=>"User Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                "status"=>404,
                "message"=>"User not found",
            ],404);
        }

    }
    public function profile(Request $request)
    {
        // Authenticated user
        $user = $request->user();

        return response()->json([
            'user' => $user
        ]);
    }
}
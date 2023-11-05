<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['login', 'register','profile']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->guard('web')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->guard('web')->user();  // Get the authenticated user
        $store = $user->stores()->first();  // Modify to fit your data structure

        return $this->createNewToken($request, $token, $store);
    }

    protected function createNewToken(Request $request, $token, $store)
    {
        $user = $request->user();
        $role = ''; // Get the user role based on your application's logic

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('web')->factory()->getTTL() * 60,
            'user' => $user,
            'role' => $role,
            'store' => $store ?? null,  // Return null if no data is found
        ]);
    }



    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|between:2,100',
            'name_en' => 'string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        if ($user) {
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        }

    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function profile(Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => $user,
        ]);
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreRequest;
use App\Http\Requests\Store\UpdateRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        if ($stores->count()>0){

            return response()->json([
                "status"=>200,
                "stores"=>$stores,
            ],200);
        }
        else{
            return response()->json([
                "status"=>404,
                "stores"=>"no stores found",
            ],404);
        }
    }
    public function show($id)
    {
        $store = Store::find($id);

        if ($store) {
            return response()->json([
                "status"=>200,
                "store" => $store
            ], 200);
        }else{
            return response()->json([
                "status"=>404,
                "message" => "Store not found"
            ], 404);
        }
    }
    public function destroy($id)
    {
        $store = Store::find($id);

        if ($store) {
            $store->delete();
            return response()->json([
                "status" => 200,
                "message" => "Store Deleted Successfully",
            ], 200);
        } else {
            return response()->json([
                "status" => 404,
                "message" => "Store not found",
            ], 404);
        }
    }
}
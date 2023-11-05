<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Zone\StoreRequest;
use App\Http\Requests\Zone\UpdateRequest;
use App\Models\Branch;
class ZoneController extends Controller
{
    //public function index()
    //{
    //    $user = Auth::guard('web')->user();
    //
    //    if (!$user) {
    //        return response()->json([
    //            "status" => 401,
    //            "message" => "Unauthorized",
    //        ], 401);
    //    }
    //
    //    $store = $user->stores()->first();
    //
    //    if (!$store) {
    //        return response()->json([
    //            "status" => 404,
    //            "message" => "No store found for this user",
    //        ], 404);
    //    }
    //
    //    $branches = $store->branches;
    //
    //    if ($branches->count() === 0) {
    //        return response()->json([
    //            "status" => 404,
    //            "zones" => "No branches found for this store",
    //        ], 404);
    //    }
    //
    //    $zones = collect();
    //
    //    foreach ($branches as $branch) {
    //        $zones = $zones->merge($branch->zones);
    //    }
    //
    //    if ($zones->count() > 0) {
    //        return response()->json([
    //            "status" => 200,
    //            "zones" => $zones,
    //        ], 200);
    //    } else {
    //        return response()->json([
    //            "status" => 404,
    //            "zones" => "No zones found for this store's branches",
    //        ], 404);
    //    }
    //}
    //public function index()
    //{
    //    $user = Auth::guard('web')->user();
    //
    //    if (!$user) {
    //        return response()->json([
    //            "status" => 401,
    //            "message" => "Unauthorized",
    //        ], 401);
    //    }
    //
    //    $store = $user->stores()->first();
    //
    //    if (!$store) {
    //        return response()->json([
    //            "status" => 404,
    //            "message" => "No store found for this user",
    //        ], 404);
    //    }
    //
    //    // Get the first branch for this store
    //    $branch = $store->branches()->first();
    //
    //    if (!$branch) {
    //        return response()->json([
    //            "status" => 404,
    //            "zones" => "No branch found for this store",
    //        ], 404);
    //    }
    //
    //    $zones = $branch->zones;
    //
    //    if ($zones->count() > 0) {
    //        return response()->json([
    //            "status" => 200,
    //            "zones" => $zones,
    //        ], 200);
    //    } else {
    //        return response()->json([
    //            "status" => 404,
    //            "zones" => "No zones found for the first branch of this store",
    //        ], 404);
    //    }
    //}
    public function index()
    {
        // Authenticate the user
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }

        // Retrieve the user's store
        $store = $user->stores->first();

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }
        // Retrieve zones belonging to branches of the user's store
        $zones = $store->branches()->with('zones')->get()->pluck('zones')->flatten();

        return response()->json([
            "status" => 200,
            "zones" => $zones,
        ], 200);
    }

    public function show($id)
    {
        // Authenticate the user
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }

        // Retrieve the user's store
        $store = $user->stores->first();

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }

        // Retrieve the specified zone belonging to branches of the user's store
        $zone = $store->branches()->with('zones')->whereHas('zones', function ($query) use ($id) {
            $query->where('zones.id', $id);
        })->first();

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "zone" => $zone,
        ], 200);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        // Retrieve the branch ID from the request data
        $branchId = $data['branch_id'];

        $branch = Branch::find($branchId);

        if (!$branch) {
            return response()->json([
                "status" => 404,
                "message" => "Branch not found.",
            ], 404);
        }

        // Create a zone for the branch
        $zone = $branch->zones()->create($data);

        return response()->json([
            "status" => 200,
            "message" => "Zone created and associated with the branch successfully",
            "zone" => $zone,
        ], 200);
    }
    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        // Retrieve the branch ID from the request data
        $branchId = $data['branch_id'];

        $branch = Branch::find($branchId);

        if (!$branch) {
            return response()->json([
                "status" => 404,
                "message" => "Branch not found.",
            ], 404);
        }

        $zone = $branch->zones()->find($id);

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        $zone->update($data);

        return response()->json([
            "status" => 200,
            "message" => "Zone updated and associated with the branch successfully",
            "zone" => $zone,
        ], 200);
    }


    public function destroy($id)
    {
        // Authenticate the user
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }

        // Retrieve the user's store
        $store = $user->stores->first();

        if (!$store) {
            return response()->json([
                "status" => 404,
                "message" => "Store not found for this user.",
            ], 404);
        }

        // Retrieve the specified zone belonging to branches of the user's store
        $zone = $store->branches()->with('zones')->whereHas('zones', function ($query) use ($id) {
            $query->where('zones.id', $id);
        })->first();

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        // Delete the zone
        $zone->delete();

        return response()->json([
            "status" => 200,
            "message" => "Zone deleted successfully.",
        ], 200);
    }
}

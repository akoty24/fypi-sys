<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Models\Table;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function index($zoneId)
    {
        // Authenticate the user
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }

        // Retrieve the specified zone belonging to branches of the user's store
        $zone = Zone::with('tables')->find($zoneId);

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        // Retrieve tables associated with the specified zone
        $tables = $zone->tables;

        return response()->json([
            "status" => 200,
            "tables" => $tables,
        ], 200);

    }
    public function store(StoreTableRequest $request)
    {
        $data = $request->validated();

        $zone_id = $request->input('zone_id'); // Get zone_id from the request payload

        $zone = Zone::find($zone_id);

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        $table = $zone->tables()->create($data);

        return response()->json([
            "status" => 200,
            "message" => "Table created and associated with the zone successfully",
            "table" => $table,
        ], 200);
    }
    public function update(UpdateTableRequest $request, $table_id)
    {
        $data = $request->validated();

        $table = Table::find($table_id);

        if (!$table) {
            return response()->json([
                "status" => 404,
                "message" => "Table not found.",
            ], 404);
        }

        $table->update($data);

        return response()->json([
            "status" => 200,
            "message" => "Table updated successfully",
            "table" => $table,
        ], 200);
    }
    public function show($zoneId, $tableId)
    {
        // Authenticate the user
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }

        // Retrieve the specified zone belonging to branches of the user's store
        $zone = Zone::with('tables')->find($zoneId);

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        // Retrieve the specified table associated with the zone
        $table = $zone->tables()->find($tableId);

        if (!$table) {
            return response()->json([
                "status" => 404,
                "message" => "Table not found.",
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "table" => $table,
        ], 200);
    }
    public function destroy($zoneId, $tableId)
    {
        // Authenticate the user
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                "status" => 401,
                "message" => "Unauthenticated.",
            ], 401);
        }

        // Retrieve the specified zone belonging to branches of the user's store
        $zone = Zone::with('tables')->find($zoneId);

        if (!$zone) {
            return response()->json([
                "status" => 404,
                "message" => "Zone not found.",
            ], 404);
        }

        // Retrieve the specified table associated with the zone
        $table = $zone->tables()->find($tableId);

        if (!$table) {
            return response()->json([
                "status" => 404,
                "message" => "Table not found.",
            ], 404);
        }

        // Delete the table
        $table->delete();

        return response()->json([
            "status" => 200,
            "message" => "Table deleted successfully.",
        ], 200);
    }

}
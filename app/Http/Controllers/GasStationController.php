<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GasStation;
use Validator;

class GasStationController extends Controller
{
    public function index() {
        try {
            $stations = GasStation::all();

            if(!sizeOf($stations)) {
                return response()->json([
                    "message" => "No data yet"
                ], 200);
            }

            return response()->json([
                "message" => "succeeded",
                "stations" => $stations
            ], 200);

        } catch(Throwable $e) {

            return response()->json([
                "message" => "failed",
                "errors" => $e
            ]);

        }
    }

    public function details(Request $request) {
        $station = GasStation::find($request->id);
        if(!$station) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }
        $station->township->with("region");
        $station->prices;
        return response()->json([
            "message" => "succeeded",
            "details" => $station
        ]);
    }

    public function create(Request $request) {
        $input = $request->only(["price_id", "township_id", "name", "available_fuel", "address", "longitude", "latitude"]);

        $validator = Validator::make($input, [
            "township_id" => "required|exists:townships,id|integer",
            "name" => "required|unique:gas_stations|string",
            "available_fuel" => "required|array|in:92,95,diesel,premium_diesel",
            "price_id" => "required|exists:prices,id|integer",
            "address" => "required|string|unique:gas_stations",
            "longitude" => "integer|unique:gas_stations",
            "latitude" => "integer|unique:gas_stations",
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $station = GasStation::create($input);
            $station->prices()->attach($input["price_id"]);
            return response()->json([
                'message' => 'succeeded',
                'data' => $station
            ], 201);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function update(Request $request) {
        $station = GasStation::find($request->id);
        if(!$station) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        $input = $request->only(["price_id", "township_id", "name", "available_fuel", "address", "longitude", "latitude"]);

        if(!$input) {
            return response()->json([
                "message" => "nothing changed"
            ]);
        }

        $validator = Validator::make($input, [
            "township_id" => "exists:townships,id|integer",
            "name" => "unique:gas_stations|string",
            "available_fuel" => "array|in:92,95,diesel,premium_diesel",
            "price_id" => "exists:prices,id|integer",
            "address" => "string|unique:gas_stations",
            "longitude" => "integer|unique:gas_stations",
            "latitude" => "integer|unique:gas_stations",
        ]);

        
        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $station->update($input);
            return response()->json([
                "message" => "succeeded",
                "new_region" => $station
            ]);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function destroy(Request $request) {
        $station = GasStation::find($request->id);
        if(!$station) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        try {
            $station->destroy($request->id);
            return response()->json([
                "message" => "successfully deleted"
            ]);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }
}

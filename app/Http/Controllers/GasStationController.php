<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GasStation;
use Validator;

class GasStationController extends Controller
{
    public function __construct() {
        $this->middleware("auth:api", ['except' => ['index', 'details']]);
    }

    public function index() {
        try {
            $stations = GasStation::paginate(10);

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
        $station->township;
        $region = $station->region($request->id);
        $station->prices;
        return response()->json([
            "message" => "succeeded",
            "details" => [
                "gas_stations" => $station,
                "region" => $region
            ]
        ]);
    }

    public function create(Request $request) {
        $input = $request->only(["township_id", "name", "has_gas", "available_fuel", "address", "longitude", "latitude"]);

        $validator = Validator::make($input, [
            "township_id" => "required|exists:townships,id|integer",
            "name" => "required|string",
            "description" => "string|max:255",
            "has_gas" => "boolean",
            "available_fuel" => "exclude_if:has_gas,true|required|array|in:92,95,97,diesel,premium_diesel",
            "address" => "required|string|unique:gas_stations",
            "longitude" => "integer|unique:gas_stations",
            "latitude" => "integer|unique:gas_stations"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            if(isset($input["has_gas"]) && $input["has_gas"]) {
                $input["available_fuel"] = ["gas"];
            }
            
            $station = GasStation::create($input);

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

        $input = $request->only(["description", "township_id", "name", "has_gas", "available_fuel", "address", "longitude", "latitude"]);

        if(!$input) {
            return response()->json([
                "message" => "nothing changed"
            ]);
        }

        $validator = Validator::make($input, [
            "description" => "string|max:255",
            "township_id" => "exists:townships,id|integer",
            "name" => "string",
            "has_gas" => "boolean",
            "available_fuel" => "exclude_if:has_gas,true|array|in:92,95,97,diesel,premium_diesel",
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
            if(isset($input["has_gas"]) && $input["has_gas"]) {
                $station->available_fuel = ["gas"];
            }

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

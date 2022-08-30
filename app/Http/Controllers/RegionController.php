<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use Validator;

class RegionController extends Controller
{
    public function __construct() {
        $this->middleware("auth:api", ['except' => ['index', 'details']]);
    }

    public function index() {
        try {
            $regions = Region::paginate(10);

            if(!sizeOf($regions)) {
                return response()->json([
                    "message" => "No data yet"
                ], 200);
            }

            return response()->json([
                "message" => "succeeded",
                "regions" => $regions
            ], 200);

        } catch(Throwable $e) {

            return response()->json([
                "message" => "failed",
                "errors" => $e
            ]);

        }
    }

    public function details(Request $request) {
        
        $region = Region::find($request->id);
        $region->id = $request->id;

        if(!$region) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }
        $region->townships;
        $gas_stations = $region->gas_stations($request->id);
        
        return response()->json([
            "message" => "succeeded",
            "details" => [
                "region" => $region,
                "gas_stations" => $gas_stations
            ]
        ]);
    }

    public function create(Request $request) {
        $input = $request->only(["name"]);

        $validator = Validator::make($input, [
            "name" => "required|unique:regions|string"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $region = Region::create($input);
            return response()->json([
                'message' => 'succeeded',
                'data' => $region
            ], 201);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function update(Request $request) {
        $region = Region::find($request->id);
        if(!$region) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        $input = $request->only(["name"]);
        if(!$input) {
            return response()->json([
                "message" => "nothing changed"
            ]);
        }
        
        $validator = Validator::make($input, [
            "name" => "unique:regions|string"
        ]);

        
        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        $region->name = $input["name"];
        try {
            $region->save($input);
            return response()->json([
                "message" => "succeeded",
                "new_region" => $region
            ]);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function destroy(Request $request) {
        $region = Region::find($request->id);
        if(!$region) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        try {
            $region->destroy($request->id);
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

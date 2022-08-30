<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Enums\FuelType;
use Validator;
use App\Models\Price;
use App\Models\GasStation;

class PriceController extends Controller
{
    public function __construct() {
        $this->middleware("auth:api", ['except' => ['index', 'details']]);
    }

    public function index() {
        try {
            $prices = Price::paginate(10);

            if(!sizeOf($prices)) {
                return response()->json([
                    "message" => "No data yet"
                ], 200);
            }

            return response()->json([
                "message" => "succeeded",
                "regions" => $prices
            ], 200);

        } catch(Throwable $e) {

            return response()->json([
                "message" => "failed",
                "errors" => $e
            ]);

        }
    }

    public function details(Request $request) {
        $price = Price::find($request->id);
        if(!$price) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }
        
        $price->gas_station;
        $region = $price->regions($request->id);
        $township = $price->townships($request->id);
        return response()->json([
            "message" => "succeeded",
            "details" => [
                "price" => $price,
                "region" => $region,
                "township" => $township
            ]
        ]);
    }

    public function create(Request $request) {
        $input = $request->only(["station_id", "price", "date", "fuel_type"]);
        $validator = Validator::make($input, [
            "price" => "required|integer",
            "date" => "required|date",
            "fuel_type" => ["required", "string", new Enum(FuelType::class)],
            "station_id" => "required|integer|exists:gas_stations,id"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        $query = GasStation::select("id", "available_fuel")->where("id", $input["station_id"])->get();

        foreach($query as $qq) {
            foreach($qq["available_fuel"] as $q) {
                if($q == $input["fuel_type"]) {
                    try {
                        $price = Price::create($input);
                        return response()->json([
                            'message' => 'succeeded',
                            'data' => $price
                        ], 201);
                    } catch(Throwable $e) {
                        return response()->json([
                            'message' => 'Unknown Error',
                            'data' => []
                        ],500);
                    }
                }
            }
        }
        
        return response()->json([
            "message" => "The fuel type and station's fuel do not match",
            "fuel_type" => $input["fuel_type"],
            "station's fuel" => $qqs
        ]);
    }

    public function update(Request $request) {
        $price = Price::find($request->id);
        if(!$price) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        $input = $request->only(["station_id", "price", "date", "fuel_type"]);
        if(!$input) {
            return response()->json([
                "message" => "nothing changed"
            ]);
        }

        $validator = Validator::make($input, [
            "price" => "integer",
            "date" => "date",
            "fuel_type" => ["string", new Enum(FuelType::class)],
            "station_id" => "integer|exists:gas_stations,id"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $price->update($input);
            return response()->json([
                "message" => "succeeded",
                "new_region" => $price
            ]);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function destroy(Request $request) {
        $price = Price::find($request->id);
        if(!$price) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        try {
            $price->destroy($request->id);
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

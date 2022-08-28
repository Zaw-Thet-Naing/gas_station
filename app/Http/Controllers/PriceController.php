<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Enums\FuelType;
use Validator;
use App\Models\Price;

class PriceController extends Controller
{
    public function index() {
        try {
            $prices = Price::all();

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
        
        $price->gas_stations;
        return response()->json([
            "message" => "succeeded",
            "details" => $price 
        ]);
    }

    public function create(Request $request) {
        $input = $request->only(["station_id", "price", "date", "fuel_type"]);
        $validator = Validator::make($input, [
            "price" => "required|integer",
            "date" => "required|date",
            "fuel_type" => "required",
            "fuel_type" => [new Enum(FuelType::class)],
            "station_id" => "required|integer|exists:gas_stations"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $price = Price::create($input);
            $price->gas_stations()->attach($input["station_id"]);
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
            "fuel_type" => [new Enum(FuelType::class)],
            "station_id" => "integer|exists:gas_stations"
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

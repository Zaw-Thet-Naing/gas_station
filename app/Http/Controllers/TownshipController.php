<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Township;
use Validator;

class TownshipController extends Controller
{
    public function index(Request $request) {
        // $region = $request->region;
        $townships = Township::all();

        try {
            // $townships = Township::with(['region'])->whereHas('region', fn($query) => $query->where('name',$region))->get();

            // // if(isset($region))
            // // {
            // //     $townships->region->where('name', $region)->get();
            // // }

            if(!sizeOf($townships)) {
                return response()->json([
                    "message" => "No data yet"
                ], 200);
            }

            return response()->json([
                "message" => "succeeded",
                "townships" => $townships
            ], 200);

        } catch(Throwable $e) {

            return response()->json([
                "message" => "failed",
                "errors" => $e
            ]);

        }
    }

    public function details(Request $request) {
        $township = Township::find($request->id);
        if(!$township) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }
        $township->region;
        $township->gas_stations;
        return response()->json([
            "message" => "succeeded",
            "details" => $township
        ]);
    }

    public function create(Request $request) {
        $input = $request->only(["region_id", "name"]);
        $validator = Validator::make($input, [
            "region_id" => "required|exists:regions,id|integer",
            "name" => "required|unique:townships|string"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $township = Township::create($input);
            return response()->json([
                'message' => 'succeeded',
                'data' => $township
            ], 201);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function update(Request $request) {
        $township = Township::find($request->id);
        if(!$township) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        $input = $request->only(["region_id", "name"]);
        if(!$input) {
            return response()->json([
                "message" => "nothing changed"
            ]);
        }

        $validator = Validator::make($input, [
            "region_id" => "exits:regions,id|integer",
            "name" => "unique:regions|string"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            $township->update($input);
            return response()->json([
                "message" => "succeeded",
                "new_region" => $township
            ]);
        } catch(Throwable $e) {
            return response()->json([
                'message' => 'Unknown Error',
                'data' => []
            ],500);
        }
    }

    public function destroy(Request $request) {
        $township = Township::find($request->id);
        if(!$township) {
            return response()->json([
                "message" => "resource not found",
            ]);
        }

        try {
            $township->destroy($request->id);
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

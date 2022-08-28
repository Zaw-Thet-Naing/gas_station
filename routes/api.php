<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TownshipController;
use App\Http\Controllers\GasStationController;
use App\Http\Controllers\PriceController;
use App\Models\GasStation;
use App\Models\Township;
use App\Models\Region;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(RegionController::class)->group(function() {
    Route::get("/regions", "index");
    Route::get("/regions/{id}", "details");
    Route::post("/regions", "create");
    Route::put("/regions/{id}", "update");
    Route::delete("/regions/{id}", "destroy");
});

Route::controller(TownshipController::class)->group(function() {
    Route::get("/townships", "index");
    Route::get("/townships/{id}", "details");
    Route::post("/townships", "create");
    Route::put("/townships/{id}", "update");
    Route::delete("/townships/{id}", "destroy");
});

Route::controller(GasStationController::class)->group(function() {
    Route::get("/stations", "index");
    Route::get("/stations/{id}", "details");
    Route::post("/stations", "create");
    Route::put("/stations/{id}", "update");
    Route::delete("/stations/{id}", "destroy");
});

Route::controller(PriceController::class)->group(function() {
    Route::get("/prices", "index");
    Route::get("/prices/{id}", "details");
    Route::post("/prices", "create");
    Route::put("/prices/{id}", "update");
    Route::delete("/prices/{id}", "destroy");
});
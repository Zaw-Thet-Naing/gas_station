<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GasStation;
use App\Models\Region;
use App\Models\Township;

class Price extends Model
{
    use HasFactory;

    protected $fillable = ["price", "date", "fuel_type", "station_id"];

    public function gas_station() {
        return $this->belongsTo(GasStation::class, "station_id");
    }

    public function regions($id) {
        return Region::whereIn("id", function($q) use ($id){
            $q->from("townships")->select("region_id")->whereIn("id", function($q) use ($id){
                $q->from("gas_stations")->select("township_id")->whereIn("id", function($q) use ($id){
                    $q->from("prices")->select("station_id")->where("id", $id);
                });
            });
        })->get();
    }

    public function townships($id) {
        return Township::whereIn("id", function($q) use ($id) {
            $q->from("gas_stations")->select("township_id")->whereIn("id", function($q) use($id) {
                $q->from("prices")->select("station_id")->where("id", $id);
            });
        })->get();
    }
}

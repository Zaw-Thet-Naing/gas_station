<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Township;
use App\Models\Price;
use App\Models\Region;

class GasStation extends Model
{
    use HasFactory;

    protected $fillable = ["description", "township_id", "name", "available_fuel", "address", "longitude", "latitude", "has_gas"];

    protected $casts = ["available_fuel" => "array"];

    public function township() {
        return $this->belongsTo(Township::class);
    }

    public function region($id) {
        return Region::whereIn("id", function($q) use($id) {
            $q->from("townships")->select("region_id")->whereIn("id", function($q) use($id) {
                $q->from("gas_stations")->select("township_id")->where("id", $id);
            });
        })->get();
    }

    public function prices() {
        return $this->hasMany(Price::class, "station_id");
    }
}


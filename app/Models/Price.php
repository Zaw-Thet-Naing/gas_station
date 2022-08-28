<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GasStation;

class Price extends Model
{
    use HasFactory;

    protected $fillable = ["price", "date", "fuel_type"];

    public function gas_stations() {
        return $this->belongsToMany(GasStation::class, "price_station", "price_id", "station_id");
    }

    public function regions() {
        
    }

    public function townships() {

    }
}

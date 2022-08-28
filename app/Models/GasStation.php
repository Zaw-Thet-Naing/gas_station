<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Township;
use App\Models\Price;

class GasStation extends Model
{
    use HasFactory;

    protected $fillable = ["township_id", "name", "available_fuel", "address", "longitude", "latitude"];

    protected $casts = ["available_fuel" => "array"];

    public function township() {
        return $this->belongsTo(Township::class);
    }

    public function prices() {
        return $this->belongsToMany(Price::class, "price_station", "station_id", "price_id");
    }
}


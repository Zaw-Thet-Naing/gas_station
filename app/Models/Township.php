<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Region;
use App\Models\GasStation;

class Township extends Model
{
    use HasFactory;

    protected $fillable = ["region_id", "name"];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function gas_stations() {
        return $this->hasMany(GasStation::class);
    }
}

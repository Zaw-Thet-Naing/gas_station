<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Township;
use App\Models\GasStation;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function townships() {
        return $this->hasMany(Township::class);
    }

    public function gas_stations($id) {
        return GasStation::whereIn("township_id", function($q) use ($id){
            $q->from("townships")->select("id")->whereIn("region_id", function($q) use ($id){
                $q->from("regions")->select("id")->where("id", $id);
            });
        })->get();
    }
}

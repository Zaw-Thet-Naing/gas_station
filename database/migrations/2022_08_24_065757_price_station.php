<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("price_station", function(Blueprint $table) {
            $table->unsignedBigInteger("price_id");
            $table->unsignedBigInteger("station_id");
            $table->foreign("price_id")->references("id")->on("prices")->onDelete("cascade");
            $table->foreign("station_id")->references("id")->on("gas_stations")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

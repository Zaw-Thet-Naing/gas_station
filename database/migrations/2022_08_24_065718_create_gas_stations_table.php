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
        Schema::create('gas_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("township_id")->constrained("townships");
            $table->string("name");
            $table->string("description")->nullable();
            $table->boolean("has_gas")->default(false);
            $table->json("available_fuel")->nullable();
            $table->string("address");
            $table->decimal("longitude", 11, 8)->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gas_stations');
    }
};

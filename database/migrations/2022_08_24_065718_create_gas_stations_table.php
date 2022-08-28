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
            $table->unsignedBigInteger("township_id");
            $table->string("name");
            $table->json("available_fuel");
            $table->string("address");
            $table->decimal("longitude", 10, 8)->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->foreign("township_id")->references("id")->on("townships")->onDelete("cascade");
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

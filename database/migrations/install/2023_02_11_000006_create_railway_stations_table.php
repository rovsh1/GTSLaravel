<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_railway_stations', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('city_id')->unsigned();
            $table->string('name');

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('r_railway_stations');
    }
};
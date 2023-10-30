<?php

use App\Shared\Support\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_railway_stations', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->unsignedInteger('city_id');

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('r_railway_stations'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_railway_stations_translation');
        Schema::dropIfExists('r_railway_stations');
    }
};

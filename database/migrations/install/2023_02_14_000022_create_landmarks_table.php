<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_landmarks', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->unsignedInteger('city_id');
            $table->unsignedTinyInteger('location_type')->default(0);
            $table->string('address');
            $table->double('address_lat', 11, 8)->nullable();
            $table->double('address_lon', 11, 8)->nullable();
            $table->integer('city_distance')->nullable();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_landmark_types')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->index('city_distance');
        });

        (new TranslationTable('r_landmarks'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_landmarks');
    }
};

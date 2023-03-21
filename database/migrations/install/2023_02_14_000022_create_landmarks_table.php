<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_landmarks', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('type_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned();
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

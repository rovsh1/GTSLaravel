<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('city_id')->unsigned();
            $table->smallInteger('type_id')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('visibility')->unsigned()->default(0);
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->string('name', 100);
            $table->char('zipcode', 6)->nullable();
            $table->string('address');
            $table->double('address_lat', 11, 8)->nullable();
            $table->double('address_lon', 11, 8)->nullable();
            $table->integer('city_distance')->nullable();
            $table->text('markup_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};

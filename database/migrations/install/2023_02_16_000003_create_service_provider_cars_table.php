<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_provider_cars', function (Blueprint $table) {
            $table->unsignedInteger('car_id');
            $table->unsignedInteger('provider_id');

            $table->unique(['car_id', 'provider_id',], 'service_provider_cars_uid');

            $table->foreign('car_id', 'fk_service_provider_cars_car_id')
                ->references('id')
                ->on('r_transport_cars')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('provider_id', 'fk_service_provider_cars_provider_id')
                ->references('id')
                ->on('service_providers')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_cars');
    }
};

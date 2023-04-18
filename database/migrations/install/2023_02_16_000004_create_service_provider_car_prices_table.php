<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_provider_car_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('season_id');
            $table->unsignedInteger('city_id');
            $table->unsignedSmallInteger('car_id');
            $table->unsignedSmallInteger('currency_id');
            $table->unsignedDecimal('price_net');
            $table->unsignedDecimal('price_gross');

//            $table->unique(['car_id', 'city_id', 'service_id', 'currency_id'], 'uid');

            $table->foreign('season_id', 'fk_service_provider_car_prices_season_id')
                ->references('id')
                ->on('service_provider_seasons')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('city_id', 'fk_service_provider_car_prices_city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('service_id', 'fk_service_provider_car_prices_service_id')
                ->references('id')
                ->on('service_provider_services')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('currency_id', 'fk_service_provider_car_prices_currency_id')
                ->references('id')
                ->on('r_currencies')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('car_id', 'fk_service_provider_car_prices_car_id')
                ->references('id')
                ->on('r_transport_cars')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_car_prices');
    }
};

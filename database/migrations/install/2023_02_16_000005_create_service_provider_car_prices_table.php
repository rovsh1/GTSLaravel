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
            $table->unsignedInteger('season_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('car_id');
            $table->unsignedSmallInteger('currency_id');
            $table->unsignedDecimal('price_net');
            $table->text('prices_gross');//json('prices_gross')

//            $table->unique(['car_id', 'city_id', 'service_id', 'currency_id'], 'uid');

            $table->foreign('season_id')
                ->references('id')
                ->on('service_provider_seasons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('service_id')
                ->references('id')
                ->on('service_provider_transfer_services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('currency_id')
                ->references('id')
                ->on('r_currencies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('car_id')
                ->references('id')
                ->on('service_provider_cars')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_car_prices');
    }
};

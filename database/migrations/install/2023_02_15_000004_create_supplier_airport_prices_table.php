<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_airport_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('season_id');
            $table->char('currency', 3);
            $table->unsignedDecimal('price_net');
            $table->json('prices_gross');

//            $table->unique(['car_id', 'city_id', 'service_id', 'currency'], 'uid');

            $table->foreign('service_id', 'fk_supplier_airport_prices_service_id')
                ->references('id')
                ->on('supplier_services')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('season_id', 'fk_supplier_airport_prices_season_id')
                ->references('id')
                ->on('supplier_seasons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('currency')
                ->references('code_char')
                ->on('r_currencies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_airport_prices');
    }
};

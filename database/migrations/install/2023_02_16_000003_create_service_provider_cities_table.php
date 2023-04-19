<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_provider_cities', function (Blueprint $table) {
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('provider_id');

            $table->unique(['city_id', 'provider_id',], 'service_provider_cities_uid');

            $table->foreign('city_id', 'fk_service_provider_cities_city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('provider_id', 'fk_service_provider_cities_provider_id')
                ->references('id')
                ->on('service_providers')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_cities');
    }
};

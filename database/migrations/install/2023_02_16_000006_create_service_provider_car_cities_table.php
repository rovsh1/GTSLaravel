<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_provider_car_cities', function (Blueprint $table) {
            $table->unsignedInteger('car_id');
            $table->unsignedInteger('city_id');

            $table->foreign('car_id')
                ->references('id')
                ->on('service_provider_cars')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_provider_car_cities');
    }
};

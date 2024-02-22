<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_car_bids', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('supplier_car_id');
            $table->unsignedTinyInteger('cars_count');
            $table->unsignedTinyInteger('passengers_count');
            $table->unsignedTinyInteger('baggage_count');
            $table->unsignedTinyInteger('baby_count');
            $table->json('prices');
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('supplier_car_id')
                ->references('id')
                ->on('supplier_cars')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_car_bids');
    }
};

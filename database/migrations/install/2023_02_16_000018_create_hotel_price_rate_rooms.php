<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_price_rate_rooms', function (Blueprint $table) {
            $table->unsignedInteger('rate_id');
            $table->unsignedInteger('room_id');

            $table->foreign('rate_id')
                ->references('id')
                ->on('hotel_price_rates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_price_rate_rooms');
    }
};

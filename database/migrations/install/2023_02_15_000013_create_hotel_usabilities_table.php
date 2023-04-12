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
        Schema::create('hotel_usabilities', function (Blueprint $table) {
            $table->unsignedInteger('hotel_id');
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedSmallInteger('usability_id');

            $table->unique(['hotel_id', 'room_id', 'usability_id']);

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('usability_id')
                ->references('id')
                ->on('r_hotel_usabilities')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_usabilities');
    }
};

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
        Schema::create('client_hotel_markups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedInteger('hotel_room_id');
            $table->integer('value');
            $table->unsignedTinyInteger('type');
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_room_id')
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
        Schema::dropIfExists('client_hotel_markups');
    }
};

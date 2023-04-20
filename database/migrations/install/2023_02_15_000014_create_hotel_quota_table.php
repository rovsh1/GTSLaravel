<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotel_room_quota', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->date('date');
            $table->unsignedTinyInteger('release_days')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('count_available')->default(0);
            $table->unsignedTinyInteger('count_booked')->default(0);
            $table->unsignedTinyInteger('count_reserved')->default(0);

            $table->unique(['date', 'room_id'], 'hotel_room_quota_uid');

            $table->foreign('room_id', 'fk_hotel_room_quota_room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_quota');
    }
};

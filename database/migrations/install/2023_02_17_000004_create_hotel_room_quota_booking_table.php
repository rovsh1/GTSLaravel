<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotel_room_quota_booking', function (Blueprint $table) {
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('quota_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedSmallInteger('value');
            $table->json('context')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->primary(['quota_id', 'booking_id']);

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('quota_id')
                ->references('id')
                ->on('hotel_room_quota')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_quota_booking');
    }
};

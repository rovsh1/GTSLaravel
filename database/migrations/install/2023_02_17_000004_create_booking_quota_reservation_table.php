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
        Schema::create('booking_quota_reservation', function (Blueprint $table) {
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('quota_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedSmallInteger('value');
            $table->json('context');
            $table->timestamp('created_at')->nullable();

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_quota_reservation');
    }
};

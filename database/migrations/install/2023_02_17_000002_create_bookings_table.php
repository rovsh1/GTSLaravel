<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->tinyInteger('type');
            $table->tinyInteger('status');
            $table->tinyInteger('source');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        $this->upBookingHotels();
    }

    private function upBookingHotels(): void
    {
        Schema::create('booking_hotel_details', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->primary();
            $table->unsignedInteger('hotel_id');
            $table->date('date_start');
            $table->date('date_end');
            $table->json('additional_data')->nullable();
            $table->json('rooms')->default(new Expression('(JSON_ARRAY())'));
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('booking_hotel_details');
    }
};

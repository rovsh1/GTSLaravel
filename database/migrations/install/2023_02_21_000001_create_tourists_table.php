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
        Schema::create('order_tourists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->string('name');
            $table->smallInteger('country_id')->unsigned();
            $table->unsignedTinyInteger('gender');
            $table->boolean('is_adult');
            $table->unsignedTinyInteger('age')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('country_id')
                ->references('id')
                ->on('r_countries')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        $this->upBookingHotelRoomTourists();
        $this->upBookingAirportTourists();
    }

    private function upBookingHotelRoomTourists(): void
    {
        Schema::create('booking_hotel_room_tourists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_hotel_room_id');
            $table->unsignedInteger('tourist_id');

            $table->unique(['booking_hotel_room_id', 'tourist_id'],'booking_hotel_room_tourists_room_id_tourist_id_unique');

            $table->foreign('booking_hotel_room_id')
                ->references('id')
                ->on('booking_hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('tourist_id')
                ->references('id')
                ->on('order_tourists')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upBookingAirportTourists(): void
    {
        Schema::create('booking_airport_tourists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_airport_id');
            $table->unsignedInteger('tourist_id');

            $table->unique(['booking_airport_id', 'tourist_id']);

            $table->foreign('booking_airport_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('tourist_id')
                ->references('id')
                ->on('order_tourists')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_hotel_room_tourists');
        Schema::dropIfExists('booking_airport_tourists');
        Schema::dropIfExists('order_tourists');
    }
};

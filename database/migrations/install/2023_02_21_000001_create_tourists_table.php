<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_guests', function (Blueprint $table) {
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

        $this->upBookingHotelRoomGuests();
        $this->upBookingAirportGuests();
        $this->upBookingCarBidGuests();
    }

    private function upBookingHotelRoomGuests(): void
    {
        Schema::create('booking_hotel_room_guests', function (Blueprint $table) {
            $table->unsignedInteger('accommodation_id');
            $table->unsignedInteger('guest_id');

            $table->primary(['accommodation_id', 'guest_id']);

            $table->foreign('accommodation_id')
                ->references('id')
                ->on('booking_hotel_accommodations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('guest_id')
                ->references('id')
                ->on('order_guests')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upBookingAirportGuests(): void
    {
        Schema::create('booking_airport_guests', function (Blueprint $table) {
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('guest_id');

            $table->primary(['booking_id', 'guest_id']);

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('guest_id')
                ->references('id')
                ->on('order_guests')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upBookingCarBidGuests(): void
    {
        Schema::create('booking_car_bid_guests', function (Blueprint $table) {
            $table->unsignedInteger('car_bid_id');
            $table->unsignedInteger('guest_id');

            $table->primary(['car_bid_id', 'guest_id']);

            $table->foreign('car_bid_id')
                ->references('id')
                ->on('booking_car_bids')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('guest_id')
                ->references('id')
                ->on('order_guests')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_car_bid_guests');
        Schema::dropIfExists('booking_hotel_room_guests');
        Schema::dropIfExists('booking_airport_guests');
        Schema::dropIfExists('order_guests');
    }
};

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
            $table->string('source');
            $table->unsignedInteger('creator_id');
            $table->json('price');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('creator_id')
                ->references('id')
                ->on('administrators')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        $this->upBookingHotel();
        $this->upBookingAirport();
        $this->upAdministratorBookings();
    }

    private function upBookingHotel(): void
    {
        Schema::create('booking_hotel_details', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->primary();
            $table->unsignedInteger('hotel_id');
            $table->date('date_start');
            $table->date('date_end');
            $table->unsignedInteger('nights_count');
            $table->unsignedTinyInteger('quota_processing_method');
            $table->json('data');
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

    private function upBookingAirport(): void
    {
        Schema::create('booking_airport_details', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->primary();
            $table->unsignedInteger('airport_id');
            $table->unsignedInteger('service_id');
            $table->date('date');
            $table->json('data');
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('airport_id')
                ->references('id')
                ->on('r_airports')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('service_id')
                ->references('id')
                ->on('service_provider_airport_services')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upAdministratorBookings(): void
    {
        Schema::create('administrator_bookings', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->primary();
            $table->unsignedInteger('administrator_id');

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('administrator_id')
                ->references('id')
                ->on('administrators')
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
        Schema::dropIfExists('administrator_bookings');
    }
};

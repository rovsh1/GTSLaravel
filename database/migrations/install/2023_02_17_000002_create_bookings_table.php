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
            $table->increments('id')->from(100);
            $table->unsignedInteger('order_id');
            $table->unsignedTinyInteger('service_type');
            $table->unsignedTinyInteger('status');
            $table->string('source');
            $table->unsignedInteger('creator_id');
            $table->json('prices');
            $table->json('cancel_conditions')->nullable();
            $table->string('note')->nullable();
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
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        $this->upBookingHotel();
        $this->upBookingAirport();
        $this->upBookingTransfer();
        $this->upBookingOther();
        $this->upAdministratorBookings();
    }

    private function upBookingHotel(): void
    {
        Schema::create('booking_hotel_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id')->unique();
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
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upBookingAirport(): void
    {
        Schema::create('booking_airport_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id')->unique();
            $table->unsignedInteger('service_id');
            $table->timestamp('date')->nullable();
            $table->json('data');
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('service_id')
                ->references('id')
                ->on('supplier_services')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upBookingTransfer(): void
    {
        Schema::create('booking_transfer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id')->unique();
            $table->unsignedInteger('service_id');
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->json('data');
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('service_id')
                ->references('id')
                ->on('supplier_services')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function upBookingOther(): void
    {
        Schema::create('booking_other_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id')->unique();
            $table->unsignedInteger('service_id');
            $table->json('data');
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('service_id')
                ->references('id')
                ->on('supplier_services')
                ->restrictOnDelete()
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
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('administrator_id')
                ->references('id')
                ->on('administrators')
                ->restrictOnDelete()
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
        Schema::dropIfExists('booking_other_details');
        Schema::dropIfExists('booking_transfer_details');
        Schema::dropIfExists('booking_airport_details');
    }
};

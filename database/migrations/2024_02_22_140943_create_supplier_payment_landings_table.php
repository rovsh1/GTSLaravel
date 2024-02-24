<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_payment_landings', function (Blueprint $table) {
            $table->unsignedInteger('payment_id');
            $table->unsignedInteger('booking_id');
            $table->unsignedDecimal('sum', 14);
            $table->timestamp('created_at');

            $table->unique(['payment_id', 'booking_id']);

            $table->foreign('payment_id')
                ->references('id')
                ->on('supplier_payments')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payment_landings');
    }
};

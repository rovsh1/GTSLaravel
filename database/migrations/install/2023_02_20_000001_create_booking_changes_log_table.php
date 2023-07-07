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
        Schema::create('booking_changes_log', function (Blueprint $table) {
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('booking_id')->nullable();
            $table->string('event');
            $table->unsignedTinyInteger('event_type');
            $table->text('payload')->nullable();//json('payload')
            $table->text('context');//json('context')
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_changes_log');
    }
};

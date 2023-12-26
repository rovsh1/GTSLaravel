<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_request_changes', function (Blueprint $table) {
            $table->unsignedInteger('booking_id');
            $table->string('status');
            $table->string('field');
            $table->string('description');
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['booking_id', 'field']);

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_request_changes');
    }
};

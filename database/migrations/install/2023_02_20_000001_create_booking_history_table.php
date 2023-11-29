<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_history', function (Blueprint $table) {
            $table->unsignedInteger('booking_id');
            $table->string('group');
            $table->json('payload')->nullable();
            $table->json('context');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_history');
    }
};

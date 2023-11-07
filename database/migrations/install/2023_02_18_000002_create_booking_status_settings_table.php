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
        Schema::create('booking_status_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('value');
            $table->string('type');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_uz')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();

            $table->primary(['type', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_settings');
    }
};

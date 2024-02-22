<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_status_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('entity_type');
            $table->unsignedTinyInteger('status');
            $table->string('color')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_uz')->nullable();
            $table->timestamps();

            $table->primary(['status', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_status_settings');
    }
};

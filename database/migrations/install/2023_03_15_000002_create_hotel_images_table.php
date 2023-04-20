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
        Schema::create('hotel_images', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('hotel_id');
            $table->char('image_id', 32);
            $table->unsignedSmallInteger('index')->default(0);
            $table->string('title')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('image_id')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_images');
    }
};

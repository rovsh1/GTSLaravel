<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('hotel_id');
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->string('custom_name')->nullable();
            $table->unsignedTinyInteger('rooms_number');
            $table->unsignedTinyInteger('guests_number');
            $table->unsignedSmallInteger('size')->nullable();
            $table->unsignedTinyInteger('price_discount')->nullable();
            $table->tinyInteger('data_flags');
            $table->smallInteger('index');
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('hotel_rooms'))
            ->string('name')
            ->text('text', ['nullable' => true])
            ->create();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
        Schema::dropIfExists('hotel_rooms_translation');
    }
};

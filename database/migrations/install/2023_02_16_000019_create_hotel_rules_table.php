<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
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
        Schema::create('hotel_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('hotel_rules'))
            ->string('name')
            ->text('text')
            ->create();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rules');
        Schema::dropIfExists('hotel_rules_translation');
    }
};

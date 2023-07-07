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
        Schema::create('translation_items', function (Blueprint $table) {
            $table->string('name', 191)->primary();//@todo hack на проде не давал создать колонку с длинной 255 "Specified key was too long; max key length is 767 bytes"
            $table->string('value_ru')->nullable();
            $table->string('value_en')->nullable();
            $table->string('value_uz')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_items');
    }
};

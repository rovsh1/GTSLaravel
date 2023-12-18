<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_hotel_meal_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type');
        });

        TranslationSchema::create('r_hotel_meal_plans', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_hotel_meal_plans');
        Schema::dropIfExists('r_hotel_meal_plans');
    }
};

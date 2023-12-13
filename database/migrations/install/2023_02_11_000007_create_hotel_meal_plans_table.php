<?php

use App\Shared\Support\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_hotel_meal_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type');
        });

        (new TranslationTable('r_hotel_meal_plans'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_hotel_meal_plans_translation');
        Schema::dropIfExists('r_hotel_meal_plans');
    }
};

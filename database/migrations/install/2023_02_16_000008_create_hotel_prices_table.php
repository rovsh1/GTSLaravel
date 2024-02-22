<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->createRates();
        $this->createPriceGroups();
        $this->createPrices();
    }

    private function createRates(): void
    {
        Schema::create('hotel_price_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedInteger('meal_plan_id')->nullable();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('meal_plan_id')
                ->references('id')
                ->on('r_hotel_meal_plans')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('hotel_price_rates', function (Blueprint $table) {
            $table->string('name');
            $table->string('description');
        });
    }

    private function createPriceGroups(): void
    {
        Schema::create('hotel_price_groups', function (Blueprint $table) {
            $table->increments('id');
//            $table->unsignedInteger('aggregator_id')->nullable();
            $table->unsignedInteger('rate_id');
            $table->unsignedTinyInteger('guests_count');
            $table->boolean('is_resident');

            $table->unique(['rate_id', 'guests_count', 'is_resident']);

            $table->foreign('rate_id')
                ->references('id')
                ->on('hotel_price_rates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function createPrices(): void
    {
        Schema::create('hotel_calculated_price_calendar', function (Blueprint $table) {
            $table->date('date');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('room_id');
            $table->unsignedDecimal('price', 14);
            $table->unsignedTinyInteger('fill_type');

            $table->primary(['date', 'group_id', 'room_id']);

            $table->foreign('group_id')
                ->references('id')
                ->on('hotel_price_groups')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_calculated_price_calendar');
        Schema::dropIfExists('hotel_price_groups');
        TranslationSchema::dropIfExists('hotel_price_rates');
        Schema::dropIfExists('hotel_price_rates');
    }
};

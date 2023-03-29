<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $this->createRates();
        $this->createSeasonPrices();
        $this->createPriceGroups();
        $this->createPrices();
    }

    private function createRates()
    {
        Schema::create('hotel_price_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('hotel_price_rates'))
            ->string('name')
            ->string('description')
            ->create();
    }

    private function createPriceGroups()
    {
        Schema::create('hotel_price_groups', function (Blueprint $table) {
            $table->increments('id');
//            $table->unsignedInteger('aggregator_id')->nullable();
            $table->unsignedInteger('rate_id');
            $table->unsignedTinyInteger('guests_number');
            $table->unsignedTinyInteger('residency');

            $table->unique(['rate_id', 'guests_number', 'residency']);

            $table->foreign('rate_id')
                ->references('id')
                ->on('hotel_price_rates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function createSeasonPrices()
    {
        Schema::create('hotel_season_prices', function (Blueprint $table) {
            //$table->increments('id');
            $table->unsignedInteger('season_id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('room_id');
            $table->unsignedDecimal('price', 11, 2);
            $table->unsignedSmallInteger('currency_id');
            $table->comment('Таблица цен по сезонам по умолчанию, для заполнения календаря цен');

            $table->primary(['group_id', 'season_id', 'room_id']);

            $table->foreign('season_id')
                ->references('id')
                ->on('hotel_seasons')
                ->nullOnDelete()
                ->cascadeOnUpdate();

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

            $table->foreign('currency_id')
                ->references('id')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    private function createPrices()
    {
        Schema::create('hotel_price_calendar', function (Blueprint $table) {
            $table->date('date');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('room_id');
            $table->unsignedDecimal('price', 11, 2);
            $table->unsignedSmallInteger('currency_id');
            $table->unsignedTinyInteger('fill_type');

            $table->primary(['date', 'group_id', 'room_id', 'currency_id']);

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

            $table->foreign('currency_id')
                ->references('id')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_season_prices');
        Schema::dropIfExists('hotel_price_calendar');
        Schema::dropIfExists('hotel_price_groups');
        Schema::dropIfExists('hotel_price_rates');
    }
};

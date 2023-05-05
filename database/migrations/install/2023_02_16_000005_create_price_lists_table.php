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
        Schema::create('client_currency_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedSmallInteger('currency_id');
            $table->date('date_start');
            $table->date('date_end');
            $table->unsignedFloat('rate');
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('currency_id')
                ->references('id')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        $this->createClientCurrencyRateHotels();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_currency_rates');
        Schema::dropIfExists('client_currency_rate_hotels');
    }

    private function createClientCurrencyRateHotels(): void
    {
        Schema::create('client_currency_rate_hotels', function (Blueprint $table) {
            $table->unsignedInteger('rate_id');
            $table->unsignedInteger('hotel_id');

            $table->foreign('rate_id')
                ->references('id')
                ->on('client_currency_rates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
};

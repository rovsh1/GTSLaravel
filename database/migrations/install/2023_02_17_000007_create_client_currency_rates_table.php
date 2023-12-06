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
            $table->char('currency', 3);
            $table->date('date_start');
            $table->date('date_end');
            $table->unsignedFloat('rate');
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('currency')
                ->references('code_char')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_currency_rates');
    }
};

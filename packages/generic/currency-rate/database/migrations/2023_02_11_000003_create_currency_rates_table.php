<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_currency_rates', function (Blueprint $table) {
            $table->date('date');
            $table->char('country', 2);
            $table->char('currency', 3);
            $table->unsignedDecimal('value', 14);
            $table->unsignedInteger('nominal');

            $table->unique(['date', 'currency', 'country']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r_currency_rates');
    }
};

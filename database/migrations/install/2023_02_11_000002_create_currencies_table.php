<?php

use App\Shared\Support\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_currencies', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('code_num')->unsigned();
            $table->char('code_char', 3);
            $table->string('sign', 8);

            $table->unique('code_char');
        });

        (new TranslationTable('r_currencies'))
            ->string('name')
            ->create();

        $this->createRatesTable();
    }

    private function createRatesTable()
    {
        Schema::create('r_currency_rates', function (Blueprint $table) {
            $table->date('date');
            $table->char('country', 2);
            $table->char('currency', 3);
            $table->unsignedDecimal('value', 10, 4);
            $table->unsignedInteger('nominal');

            $table->unique(['date', 'currency', 'country']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('r_currencies_translation');
        Schema::dropIfExists('r_currencies');
    }
};

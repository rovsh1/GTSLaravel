<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
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
        });

        (new TranslationTable('r_currencies'))
            ->string('name')
            ->create();

        $this->createRatesTable();
    }

    private function createRatesTable()
    {
        Schema::create('r_currency_rates', function (Blueprint $table) {
            $table->unsignedInteger('country_id');
            $table->unsignedSmallInteger('currency_id');
            $table->date('date');
            $table->decimal('rate', 8, 2)->unsigned();

            $table->foreign('country_id')
                ->references('id')
                ->on('r_countries')
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
        Schema::dropIfExists('r_currencies_translation');
        Schema::dropIfExists('r_currencies');
    }
};

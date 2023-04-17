<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_countries', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('currency_id')->unsigned()->nullable();
            $table->char('code', 2);
            $table->boolean('default')->default(false);
            $table->char('language', 2);
            $table->string('phone_code', 8)->nullable();
            $table->string('datetime_format', 20)->nullable();
            $table->string('date_format', 20)->nullable();
            $table->string('time_format', 20)->nullable();
//            $table->softDeletes();

            $table->foreign('currency_id')
                ->references('id')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('r_countries'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_countries_translation');
        Schema::dropIfExists('r_countries');
    }
};

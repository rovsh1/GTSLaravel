<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_hotel_usabilities', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('group_id')->unsigned();
            $table->boolean('popular')->default(false);

            $table->foreign('group_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('r_hotel_usabilities'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_hotel_usabilities_translation');
        Schema::dropIfExists('r_hotel_usabilities');
    }
};

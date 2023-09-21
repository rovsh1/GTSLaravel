<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_locale_dictionary', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique('key');
        });

        Schema::create('r_locale_dictionary_values', function (Blueprint $table) {
            $table->unsignedInteger('dictionary_id');
            $table->char('locale', 2);
            $table->text('value')->nullable();

            $table->foreign('dictionary_id')
                ->references('id')
                ->on('r_locale_dictionary')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->index(['dictionary_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('r_locale_dictionary_values');
        Schema::dropIfExists('r_locale_dictionary');
    }
};

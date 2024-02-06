<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_locale_dictionary', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('key');
        });

        Schema::create('r_locale_dictionary_values', function (Blueprint $table) {
            $table->unsignedInteger('dictionary_id');
            $table->char('language', 2);
            $table->text('value')->nullable();

            $table->foreign('dictionary_id')
                ->references('id')
                ->on('r_locale_dictionary')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->index(['dictionary_id', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r_locale_dictionary_values');
        Schema::dropIfExists('r_locale_dictionary');
    }
};

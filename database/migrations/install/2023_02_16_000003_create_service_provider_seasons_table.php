<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_provider_seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id');
            $table->string('number')->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('provider_id', 'fk_service_provider_seasons_provider_id')
                ->references('id')
                ->on('service_providers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_seasons');
    }
};

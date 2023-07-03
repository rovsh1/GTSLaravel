<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_provider_airports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id');
            $table->unsignedInteger('airport_id');

            $table->foreign('airport_id')
                ->references('id')
                ->on('r_airports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('provider_id')
                ->references('id')
                ->on('service_providers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_airports');
    }
};

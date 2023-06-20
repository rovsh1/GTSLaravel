<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_provider_transfer_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id');
            $table->string('name');
            $table->unsignedTinyInteger('type');
            $table->timestamps();

            $table->foreign('provider_id')
                ->references('id')
                ->on('service_providers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

//        (new TranslationTable('r_transfer_services'))
//            ->string('name')
//            ->create();
    }

    public function down()
    {
//        Schema::dropIfExists('r_transfer_services_translation');
        Schema::dropIfExists('service_provider_transfer_services');
    }
};

<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_airport_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->string('name');
            $table->unsignedTinyInteger('type');
            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
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
        Schema::dropIfExists('supplier_airport_services');
    }
};

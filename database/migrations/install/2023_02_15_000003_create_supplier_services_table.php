<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->string('title');
            $table->unsignedTinyInteger('type');
            $table->json('data')->nullable();
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
        Schema::dropIfExists('supplier_services');
    }
};

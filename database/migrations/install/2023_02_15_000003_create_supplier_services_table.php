<?php

use App\Shared\Support\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedTinyInteger('type');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });


        (new TranslationTable('supplier_services'))
            ->string('title')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('supplier_services_translation');
        Schema::dropIfExists('supplier_services');
    }
};

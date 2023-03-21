<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('name', 50);
            $table->timestamps();
        });

        $this->createContactsTable();
    }

    private function createContactsTable()
    {
        Schema::create('provider_contacts', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('provider_id')->unsigned();
            $table->tinyInteger('type')->unsigned();
            $table->string('value');
            $table->string('description')->nullable();
            $table->boolean('main')->default(false);
            $table->timestamps();

            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provider_contacts');
        Schema::dropIfExists('providers');
    }
};

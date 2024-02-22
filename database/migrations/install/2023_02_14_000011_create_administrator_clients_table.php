<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('administrator_clients', function (Blueprint $table) {
            $table->integer('administrator_id')->unsigned();
            $table->integer('client_id')->unsigned();

            $table->primary(['client_id', 'administrator_id']);

            $table->foreign('administrator_id')
                ->references('id')
                ->on('administrators')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrator_clients');
    }
};

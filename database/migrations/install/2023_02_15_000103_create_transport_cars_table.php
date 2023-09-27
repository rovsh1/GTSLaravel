<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_transport_cars', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('type_id')->unsigned();
            $table->string('mark', 25);
            $table->string('model', 25);
            $table->tinyInteger('passengers_number')->unsigned();
            $table->tinyInteger('bags_number')->unsigned();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_transport_types')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('r_transport_cars');
    }
};
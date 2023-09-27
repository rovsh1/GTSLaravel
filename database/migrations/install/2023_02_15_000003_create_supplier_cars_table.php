<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_cars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id');
            $table->unsignedInteger('car_id');

            $table->foreign('car_id')
                ->references('id')
                ->on('r_transport_cars')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('provider_id')
                ->references('id')
                ->on('suppliers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_cars');
    }
};
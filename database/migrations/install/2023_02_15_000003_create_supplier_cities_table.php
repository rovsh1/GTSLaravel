<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('supplier_cities', function (Blueprint $table) {
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('supplier_id');

            $table->unique(['city_id', 'supplier_id',], 'supplier_cities_uid');

            $table->foreign('city_id', 'fk_supplier_cities_city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('supplier_id', 'fk_supplier_cities_supplier_id')
                ->references('id')
                ->on('suppliers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_cities');
    }
};

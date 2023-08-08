<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('city_id')->unsigned();
            $table->unsignedSmallInteger('currency_id')->nullable();
            $table->tinyInteger('type')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->string('name', 50);
            $table->mediumText('description')->nullable();
            $table->boolean('is_b2b');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('currency_id')
                ->references('id')
                ->on('r_currencies')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};

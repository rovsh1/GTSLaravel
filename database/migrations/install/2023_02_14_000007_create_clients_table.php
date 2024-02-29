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
            $table->integer('city_id')->unsigned()->nullable();
            $table->char('currency', 3)->nullable();
            $table->tinyInteger('type')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->tinyInteger('residency')->unsigned();
            $table->char('language', 2);
            $table->string('name', 50);
            $table->mediumText('description')->nullable();
            $table->boolean('is_b2b');
            $table->unsignedInteger('markup_group_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('currency')
                ->references('code_char')
                ->on('r_currencies')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('markup_group_id')
                ->references('id')
                ->on('client_markup_groups')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};

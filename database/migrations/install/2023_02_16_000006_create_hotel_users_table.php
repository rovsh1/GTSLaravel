<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotel_administrators', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('hotel_id')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->string('presentation', 100);
            $table->string('name', 100)->nullable();
            $table->string('surname', 100)->nullable();
            $table->string('patronymic', 100)->nullable();
            $table->string('login', 50)->nullable();
            $table->string('password', 60)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_administrators');
    }
};

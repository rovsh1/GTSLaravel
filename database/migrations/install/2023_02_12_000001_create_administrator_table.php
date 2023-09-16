<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->smallInteger('post_id')->unsigned()->nullable();
            $table->string('presentation', 100);
            $table->string('name', 100)->nullable();
            $table->string('surname', 100)->nullable();
            $table->tinyInteger('gender')->unsigned()->nullable();
            $table->string('login', 50)->nullable();
            $table->char('password', 60)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->char('avatar_guid', 32)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('superuser')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('post_id')
                ->references('id')
                ->on('r_enums')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('avatar_guid')
                ->references('guid')
                ->on('files')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrators');
    }
};

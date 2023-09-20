<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedSmallInteger('country_id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('presentation');
            $table->unsignedTinyInteger('gender')->nullable();
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedSmallInteger('post_id')->nullable();
            $table->string('address')->nullable();
            $table->string('note')->nullable();
            $table->unsignedTinyInteger('status');//@todo был дефолт
            $table->unsignedTinyInteger('role');//@todo был дефолт
            $table->date('birthday')->nullable();
            $table->integer('image')->nullable();//@todo что тут?
            $table->string('recovery_hash')->nullable();//@todo что тут?
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('country_id')
                ->references('id')
                ->on('r_countries')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

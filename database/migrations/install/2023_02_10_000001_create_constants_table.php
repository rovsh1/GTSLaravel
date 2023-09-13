<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_constants', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('key');
            $table->string('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('s_constants');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_landmark_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('alias', 50);
            $table->string('name', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('r_landmark_types');
    }
};

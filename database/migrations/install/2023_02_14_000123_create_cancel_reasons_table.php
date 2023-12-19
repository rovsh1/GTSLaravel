<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_cancel_reasons', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->boolean('has_description')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('r_cancel_reasons');
    }
};

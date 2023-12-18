<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_enums', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('group', 50);

            $table->index('group');
        });

        TranslationSchema::create('r_enums', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down()
    {
        TranslationSchema::dropIfExists('r_enums');
        Schema::dropIfExists('r_enums');
    }
};

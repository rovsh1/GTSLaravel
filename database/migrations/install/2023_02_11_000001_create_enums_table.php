<?php

use App\Shared\Support\Database\Schema\TranslationTable;
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

        (new TranslationTable('r_enums'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_enums_translation');
        Schema::dropIfExists('r_enums');
    }
};

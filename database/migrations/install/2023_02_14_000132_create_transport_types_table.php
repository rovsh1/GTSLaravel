<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('r_transport_types', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->char('color', 7)->nullable();
        });

        (new TranslationTable('r_transport_types'))
            ->string('name')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('r_transport_types');
    }
};

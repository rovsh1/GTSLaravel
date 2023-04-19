<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->char('guid', 32);
            $table->char('type_hash', 32);
            $table->string('type');
            $table->integer('entity_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->string('extension')->nullable();
            $table->smallInteger('index')->unsigned()->default(0);
            $table->timestamps();

            $table->primary('guid');
            $table->index(['entity_id', 'type_hash']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};

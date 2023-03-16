<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::dropIfExists('file_parts');
        Schema::rename('files', 'files_old');

        Schema::create('files', function (Blueprint $table) {
            $table->char('guid', 32);
            $table->char('type_hash', 32);
            $table->string('type', 100);
            $table->integer('entity_id')->nullable()->unsigned();
            $table->string('name', 100)->nullable();
            $table->smallInteger('index')->unsigned()->default(0);
            $table->timestamps();

            $table->primary('guid');
            $table->index(['entity_id', 'type_hash'], 'files_idx_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};

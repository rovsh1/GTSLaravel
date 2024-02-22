<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('administrator_journal_log', function (Blueprint $table) {
            $table->unsignedInteger('administrator_id');
            $table->string('administrator_presentation');
            $table->string('event');
            $table->unsignedInteger('entity_id')->nullable();
            $table->string('entity_class')->nullable();
            $table->text('payload')->nullable();
            $table->text('context');
            $table->timestamp('created_at')->useCurrent();
        });
//        DB::statement('ALTER TABLE administrator_journal_log ENGINE = MEMORY');
    }

    public function down()
    {
        Schema::dropIfExists('administrator_journal_log');
    }
};

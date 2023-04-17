<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->char('language', 2);
            $table->unsignedInteger('country_id', 2)->nullable();
            $table->string('subject');
            $table->text('body');
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('r_countries')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('s_mail_templates');
    }
};

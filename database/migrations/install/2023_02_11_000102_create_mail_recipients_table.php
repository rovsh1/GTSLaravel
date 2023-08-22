<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_mail_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template');
            $table->string('recipient_type');
            $table->string('recipient_id')->nullable();

            $table->unique(['template', 'recipient_type', 'recipient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('s_mail_recipients');
    }
};

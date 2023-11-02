<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_mail_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notification_type');
            $table->text('recipient');
            $table->boolean('disabled')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('s_mail_recipients');
    }
};

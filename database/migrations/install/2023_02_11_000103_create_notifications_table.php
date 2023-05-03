<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_notification_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notification');
            $table->string('notifiable_type');
            $table->unsignedInteger('notifiable_id')->nullable();
            $table->boolean('mail');
            $table->boolean('sms');

//            $table->unique(['notification', 'notifiable_type', 'notifiable_id'], 's_notification_recipients');
        });
    }

    public function down()
    {
        Schema::dropIfExists('s_notification_recipients');
    }
};

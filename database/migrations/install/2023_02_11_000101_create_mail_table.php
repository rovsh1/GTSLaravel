<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_mail_queue', function (Blueprint $table) {
            $table->uuid();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->mediumText('payload');
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->primary('uuid');
        });
    }

    public function down()
    {
        Schema::dropIfExists('s_mail_queue');
    }
};

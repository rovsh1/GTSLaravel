<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('traveline_reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reservation_id')->unique();
            $table->enum('status', ['new', 'modified', 'cancelled'])->default('new');
            $table->json('data');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traveline_reservations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('traveline_hotel_room_quota', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('room_id');
            $table->date('date');
            $table->unsignedTinyInteger('release_days')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedSmallInteger('count_available')->default(0);

            $table->unique(['date', 'room_id']);

            $table->foreign('room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traveline_hotel_room_quota');
    }
};

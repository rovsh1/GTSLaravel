<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_markup_group_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedInteger('hotel_room_id')->nullable();
            $table->unsignedTinyInteger('type');
            $table->integer('value');
            $table->timestamps();

            $table->foreign('group_id')
                ->references('id')
                ->on('client_markup_groups')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('hotel_room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_markup_group_rules');
    }
};

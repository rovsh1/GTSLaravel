<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedSmallInteger('name_id');
            $table->unsignedSmallInteger('type_id');
            $table->string('custom_name')->nullable()->comment('Внутреннее наименование для отеля');
            $table->unsignedTinyInteger('rooms_number');
            $table->unsignedTinyInteger('guests_number');
            $table->unsignedTinyInteger('square')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('name_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('hotel_rooms'))
            ->text('text', ['nullable' => true])
            ->create();

        $this->createBeds();
    }

    public function createBeds()
    {
        Schema::create('hotel_room_beds', function (Blueprint $table) {
            $table->unsignedInteger('room_id');
            $table->unsignedSmallInteger('type_id');
            $table->unsignedTinyInteger('beds_number');
            $table->string('beds_size')->nullable();

            $table->foreign('room_id')
                ->references('id')
                ->on('hotel_rooms')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        (new TranslationTable('hotel_rooms'))
            ->text('text', ['nullable' => true])
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('hotel_room_beds');
        Schema::dropIfExists('hotel_rooms_translation');
        Schema::dropIfExists('hotel_rooms');
    }
};

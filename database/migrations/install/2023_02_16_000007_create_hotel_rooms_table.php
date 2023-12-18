<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');
            $table->unsignedSmallInteger('type_id');
            $table->unsignedTinyInteger('rooms_number');
            $table->unsignedTinyInteger('guests_count');
            $table->unsignedSmallInteger('square')->nullable();
            $table->unsignedTinyInteger('position')->default(0);
            $table->json('markup_settings')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('hotel_rooms', function (Blueprint $table) {
            $table->text('name');
            $table->text('text')->nullable();
        });

        $this->createBeds();
    }

    public function createBeds(): void
    {
        Schema::create('hotel_room_beds', function (Blueprint $table) {
            $table->unsignedInteger('room_id');
            $table->unsignedSmallInteger('type_id');
            $table->unsignedTinyInteger('beds_number');
            $table->string('beds_size')->nullable();

            $table->primary(['room_id', 'type_id', 'beds_number']);

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
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_beds');
        TranslationSchema::dropIfExists('hotel_rooms');
        Schema::dropIfExists('hotel_rooms');
    }
};

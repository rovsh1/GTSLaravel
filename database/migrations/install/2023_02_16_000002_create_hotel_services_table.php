<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_hotel_services', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('type_id')->unsigned()->nullable();

            $table->foreign('type_id')
                ->references('id')
                ->on('r_enums')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('r_hotel_services', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_hotel_services');
        Schema::dropIfExists('r_hotel_services');
    }
};

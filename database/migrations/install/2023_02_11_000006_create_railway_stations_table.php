<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_railway_stations', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->unsignedInteger('city_id');

            $table->foreign('city_id')
                ->references('id')
                ->on('r_cities')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('r_railway_stations', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_railway_stations');
        Schema::dropIfExists('r_railway_stations');
    }
};

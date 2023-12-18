<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_cities', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->smallInteger('country_id')->unsigned();
            $table->double('center_lat', 11, 8)->nullable();
            $table->double('center_lon', 11, 8)->nullable();
            $table->unsignedTinyInteger('priority')->default(0);

            $table->foreign('country_id')
                ->references('id')
                ->on('r_countries')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('r_cities', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_cities');
        Schema::dropIfExists('r_cities');
    }
};

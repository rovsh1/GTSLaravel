<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotel_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');

            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('hotel_rules', function (Blueprint $table) {
            $table->string('name');
            $table->text('text');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('hotel_rules');
        Schema::dropIfExists('hotel_rules');
    }
};

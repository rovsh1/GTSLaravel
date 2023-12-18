<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_countries', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->char('currency', 3)->nullable();
            $table->char('code', 2);
            $table->boolean('default')->default(false);
            $table->char('language', 2);
            $table->string('phone_code', 8)->nullable();
            $table->string('datetime_format', 20)->nullable();
            $table->string('date_format', 20)->nullable();
            $table->string('time_format', 20)->nullable();
            $table->unsignedTinyInteger('priority')->default(0);
//            $table->softDeletes();

            $table->foreign('currency')
                ->references('code_char')
                ->on('r_currencies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        TranslationSchema::create('r_countries', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_countries');
        Schema::dropIfExists('r_countries');
    }
};

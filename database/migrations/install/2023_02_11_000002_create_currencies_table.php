<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('r_currencies', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('code_num')->unsigned();
            $table->char('code_char', 3);
            $table->string('sign', 8);

            $table->unique('code_char');
        });

        TranslationSchema::create('r_currencies', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('r_currencies');
        Schema::dropIfExists('r_currencies');
    }
};

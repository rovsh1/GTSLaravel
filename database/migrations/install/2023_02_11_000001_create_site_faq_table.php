<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_faq', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type');
        });

        TranslationSchema::create('site_faq', function (Blueprint $table) {
            $table->string('question');
            $table->text('answer');
        });
    }

    public function down(): void
    {
        TranslationSchema::dropIfExists('site_faq');
        Schema::dropIfExists('site_faq');
    }
};

<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('site_faq', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type');
        });

        (new TranslationTable('site_faq'))
            ->string('question')
            ->text('answer')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('site_faq_translation');
        Schema::dropIfExists('site_faq');
    }
};

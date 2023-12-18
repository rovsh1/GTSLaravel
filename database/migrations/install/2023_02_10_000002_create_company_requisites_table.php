<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_company_requisites', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('key');
        });

        TranslationSchema::create('s_company_requisites', function (Blueprint $table) {
            $table->string('value');
        });
    }

    public function down()
    {
        TranslationSchema::dropIfExists('s_company_requisites');
        Schema::dropIfExists('s_company_requisites');
    }
};

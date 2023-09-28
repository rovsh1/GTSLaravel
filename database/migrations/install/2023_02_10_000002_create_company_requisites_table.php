<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Module\Shared\Domain\Service\CompanyRequisitesInterface;

return new class extends Migration {
    public function up()
    {
        Schema::create('s_company_requisites', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('key');
        });

        (new TranslationTable('s_company_requisites'))
            ->string('value')
            ->create();
    }

    public function down()
    {
        Schema::dropIfExists('s_company_requisites_translations');
        Schema::dropIfExists('s_company_requisites');
    }
};
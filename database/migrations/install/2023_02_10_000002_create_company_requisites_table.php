<?php

use Custom\Illuminate\Database\Schema\TranslationTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Module\Shared\Application\Service\CompanyRequisites;

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

        $this->fill();
    }

    private function fill(): void
    {
        foreach (CompanyRequisites::getInstance() as $constant) {
            $id = DB::table('s_company_requisites')->insertGetId([
                'key' => $constant->key()
            ]);

            DB::table('s_company_requisites_translation')->insert([
                'translatable_id' => $id,
                'language' => 'ru',
                'value' => $constant->default()
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('s_company_requisites_translations');
        Schema::dropIfExists('s_company_requisites');
    }
};

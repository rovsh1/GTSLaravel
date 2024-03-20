<?php

use App\Shared\Support\Database\Schema\TranslationSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        TranslationSchema::table('s_company_requisites', function (Blueprint $table) {
            $table->string('name')->after('language');
        });

        foreach (app(CompanyRequisitesInterface::class) as $constant) {
            $id = DB::table('s_company_requisites')->where('key', $constant->key())->first()?->id;

            if ($id) {
                DB::table('s_company_requisites_translation')
                    ->updateOrInsert(
                        ['translatable_id' => $id, 'language' => 'ru'],
                        ['name' => $constant->name(), 'value' => $constant->default()]
                    );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        TranslationSchema::table('s_company_requisites', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};

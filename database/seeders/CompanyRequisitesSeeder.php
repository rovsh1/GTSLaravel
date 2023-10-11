<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;

class CompanyRequisitesSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('s_company_requisites')->exists()) {
            return;
        }

        foreach (app(CompanyRequisitesInterface::class) as $constant) {
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
}

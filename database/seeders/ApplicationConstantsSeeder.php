<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Shared\Domain\Service\ApplicationConstantsInterface;

class ApplicationConstantsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('s_constants')->exists()) {
            return;
        }

        $constants = app(ApplicationConstantsInterface::class);
        foreach ($constants as $constant) {
            DB::table('s_constants')->insert([
                'key' => $constant->key(),
                'value' => $constant->default()
            ]);
        }
    }
}

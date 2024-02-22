<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferencesSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('r_countries')->exists()) {
            return;
        }

        $this->execute('data_currencies');
        $this->execute('data_countries');
        $this->execute('data_cities');
    }

    private function execute(string $key): void
    {
        DB::unprepared(file_get_contents(__DIR__ . "/sql/$key.sql"));
    }
}

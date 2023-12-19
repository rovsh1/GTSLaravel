<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CancelReasonSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('r_cancel_reasons')->exists()) {
            return;
        }

        $defaultCancelReasons = [
            ['id' => 1, 'name' => 'Нет мест'],
            ['id' => 2, 'name' => 'Двойное бронирование'],
            ['id' => 3, 'name' => 'Другое', 'has_description' => true],
        ];

        DB::table('r_cancel_reasons')->insert($defaultCancelReasons);
    }
}

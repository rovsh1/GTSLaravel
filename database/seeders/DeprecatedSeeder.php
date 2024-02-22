<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @deprecated
 */
class DeprecatedSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('r_enums')->exists()) {
            return;
        }

        $path = __DIR__ . '/deprecated';
        foreach (scandir($path) as $entry) {
            if (!is_file($path . DIRECTORY_SEPARATOR . $entry)) {
                continue;
            }
            echo '    > ' . $entry . PHP_EOL;
            $x = include $path . DIRECTORY_SEPARATOR . $entry;
            $x->up();
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @deprecated
 */
class DeprecatedSeeder extends Seeder
{
    public function run(): void
    {
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

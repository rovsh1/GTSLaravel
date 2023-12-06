<?php

namespace Module\Support\LocaleTranslator\Application\UseCase;

use DirectoryIterator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Module\Support\LocaleTranslator\Model\Dictionary;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SyncTranslations implements UseCaseInterface
{
    public function execute(bool $truncate = false): void
    {
        if ($truncate) {
            Schema::disableForeignKeyConstraints();
            DB::table('r_locale_dictionary_values')->truncate();
            DB::table('r_locale_dictionary')->truncate();
            Schema::enableForeignKeyConstraints();
        }

        $this->readDir(base_path('resources/lang/ru'));
    }

    private function readDir(string $path): void
    {
        $iterator = new DirectoryIterator($path);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDot()) {
                continue;
            } elseif ($fileinfo->isFile()) {
                $this->readFile($fileinfo->getPathname());
            }
//            elseif ($fileinfo->isDir()) {
//                $this->readDir($fileinfo->getPathname(), $prefix);
//            }
        }
    }

    private function readFile(string $filename): void
    {
        if (str_ends_with($filename, '.php')) {
            $this->syncItems(substr(basename($filename), 0, -4), include $filename);
        }
//        elseif (str_ends_with($filename, '.js')) {
//            $this->syncItems(json_decode(file_get_contents($filename), true));
//        }
    }

    private function syncItems(string $prefix, array $items): void
    {
        foreach ($items as $k => $value) {
            if (empty($k)) {
                continue;
            }
            $key = $prefix . '.' . $k;
            if (Dictionary::where('key', $key)->exists()) {
                continue;
            }

            $id = Dictionary::insertGetId([
                'key' => $key
            ]);

            DB::table('r_locale_dictionary_values')->insert([
                'dictionary_id' => $id,
                'language' => 'ru',
                'value' => $value ?: $key
            ]);
        }
    }
}
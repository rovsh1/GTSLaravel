<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Support\LocaleTranslator\Model\Dictionary;

class LocaleDictionarySeeder extends Seeder
{
    private string $filename = 'dictionary.php';

    private array $langPaths = [
        'resources/lang'
    ];

    private array $actualKeys = [];

    public function run(): void
    {
        foreach ($this->langPaths as $prefix => $path) {
            $this->includeFile($path, 'ru', is_int($prefix) ? '' : $prefix);
        }

        $this->removeDeprecated();
    }

    private function includeFile(string $path, string $locale, string $prefix = ''): void
    {
        $filename = base_path("$path/$locale/$this->filename");
        if (file_exists($filename)) {
            $items = include $filename;
            $this->updateItems($items, $locale, $prefix);
        }
    }

    private function updateItems(array $items, string $locale, string $prefix): void
    {
        foreach ($items as $key => $value) {
            $itemKey = $prefix . $key;
            if (is_array($value)) {
                $this->updateItems($value, $locale, $itemKey . '.');
            } else {
                $raw = Dictionary::findByKey($itemKey);//$this->findDictionary($itemKey);
                if (!$raw) {
                    $this->insertKey($itemKey, $locale, $value);
                }
                $this->actualKeys[] = $itemKey;
            }
        }
    }

    private function insertKey(string $key, string $locale, string $value): Dictionary
    {
        $raw = Dictionary::create(['key' => $key]);

        DB::table('r_locale_dictionary_values')
            ->updateOrInsert(['dictionary_id' => $raw->id, 'language' => $locale], ['value' => $value]);

        return $raw;
    }

    private function removeDeprecated(): void
    {
        foreach (Dictionary::query()->cursor() as $r) {
            if (in_array($r->key, $this->actualKeys)) {
                continue;
            }

            $r->delete();
        }
        unset($this->actualKeys);
    }
}

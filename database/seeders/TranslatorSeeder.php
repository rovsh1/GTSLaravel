<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Support\LocaleTranslator\Model\Dictionary;
use Support\LocaleTranslator\SyncTranslationsService;

class TranslatorSeeder extends Seeder
{
    public function run(): void
    {
        if (Dictionary::exists()) {
            return;
        }

        app(SyncTranslationsService::class)->execute();
    }
}

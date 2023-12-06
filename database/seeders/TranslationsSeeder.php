<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Support\LocaleTranslator\Application\UseCase\SyncTranslations;
use Module\Support\LocaleTranslator\Model\Dictionary;

class TranslationsSeeder extends Seeder
{
    public function run(): void
    {
        if (Dictionary::exists()) {
            return;
        }

        app(SyncTranslations::class)->execute();
    }
}

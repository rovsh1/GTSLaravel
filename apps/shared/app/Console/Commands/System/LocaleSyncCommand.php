<?php

namespace App\Shared\Console\Commands\System;

use Illuminate\Console\Command;
use Module\Support\LocaleTranslator\Application\UseCase\SyncTranslations;

class LocaleSyncCommand extends Command
{
    protected $signature = 'locale:sync
    {--truncate}';

    protected $description = '';

    public function handle(): void
    {
        app(SyncTranslations::class)->execute($this->hasOption('truncate'));
    }
}
<?php

namespace Support\LocaleTranslator;

use Illuminate\Console\Command;

class LocaleSyncCommand extends Command
{
    protected $signature = 'locale:sync
    {--truncate}';

    protected $description = '';

    public function handle(): void
    {
        app(SyncTranslationsService::class)->execute($this->hasOption('truncate'));
    }
}
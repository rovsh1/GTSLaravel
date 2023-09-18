<?php

namespace Module\Support\FileStorage\Console\Commands;

use Module\Support\FileStorage\Console\Service\StorageCleaner;
use Module\Support\FileStorage\Console\Support\AbstractCommand;

class CleanStorage extends AbstractCommand
{
    protected $signature = 'file-storage:clean';

    protected $description = '';

    public function handle(): void
    {
        $storageCleaner = $this->make(StorageCleaner::class);
        $storageCleaner->clean();
    }
}
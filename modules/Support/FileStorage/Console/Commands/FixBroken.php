<?php

namespace Module\Support\FileStorage\Console\Commands;

use Module\Support\FileStorage\Console\Service\FixManager;
use Module\Support\FileStorage\Console\Support\AbstractCommand;

class FixBroken extends AbstractCommand
{
    protected $signature = 'file-storage:fix
    {--guid=}';

    protected $description = '';

    public function handle(): void
    {
        $fixManager = $this->make(FixManager::class);
        $fixManager->fix($this->option('guid'));
    }
}
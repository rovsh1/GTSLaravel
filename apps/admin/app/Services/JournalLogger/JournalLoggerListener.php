<?php

namespace App\Admin\Services\JournalLogger;

use App\Admin\Services\JournalLogger\Changes\ModelChanged;
use App\Admin\Services\JournalLogger\Changes\ModelCreated;
use App\Admin\Services\JournalLogger\Changes\ModelDeleted;

class JournalLoggerListener
{
    public function __construct(
        public readonly ChangesRegistrator $changesRegistrator
    ) {}

    public function handle(string $event, $data): void
    {
        if (str_starts_with($event, 'eloquent.updated')) {
            $changes = new ModelChanged($data[0]);
        } elseif (str_starts_with($event, 'eloquent.deleted')) {
            $changes = new ModelDeleted($data[0]);
        } elseif (str_starts_with($event, 'eloquent.created')) {
            $changes = new ModelCreated($data[0]);
        } else {
            return;
        }

        $this->changesRegistrator->register($changes);
    }
}
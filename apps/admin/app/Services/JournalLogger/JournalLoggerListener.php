<?php

namespace App\Admin\Services\JournalLogger;

use App\Admin\Services\JournalLogger\Changes\ModelChanged;
use App\Admin\Services\JournalLogger\Changes\ModelCreated;
use App\Admin\Services\JournalLogger\Changes\ModelDeleted;
use Illuminate\Support\Facades\Auth;

class JournalLoggerListener
{
    private array $skipAttributes = [
        'remember_token',
        'updated_at',
        'created_at'
    ];

    public function __construct(
        public readonly ChangesRegistrator $changesRegistrator
    ) {}

    public function handle(string $event, $data): void
    {
        if (!Auth::check() || $this->isSkipChanges($data[0])) {
            return;
        }

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

    private function isSkipChanges($model): bool
    {
        $changes = $model->getChanges();
        foreach ($this->skipAttributes as $key) {
            unset($changes[$key]);
        }

        return empty($changes);
    }
}
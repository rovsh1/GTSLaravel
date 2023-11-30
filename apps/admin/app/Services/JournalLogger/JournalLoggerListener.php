<?php

namespace App\Admin\Services\JournalLogger;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Services\JournalLogger\Changes\ModelChanged;
use App\Admin\Services\JournalLogger\Changes\ModelCreated;
use App\Admin\Services\JournalLogger\Changes\ModelDeleted;
use Illuminate\Support\Facades\Auth;

class JournalLoggerListener
{
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
        if ($model instanceof Administrator) {
            return array_key_exists('remember_token', $model->getChanges());
        }

        return false;
    }
}
<?php

namespace App\Admin\Services\JournalLogger;

use App\Admin\Services\JournalLogger\Changes\ModelChanged;
use App\Admin\Services\JournalLogger\Changes\ModelCreated;
use App\Admin\Services\JournalLogger\Changes\ModelDeleted;
use Illuminate\Support\Facades\Auth;

class JournalLoggerListener
{
    private array $ignoredAttributes = [
        'remember_token',
        'updated_at',
        'created_at'
    ];

    private array $ignoredModels = [];

    private array $ignoredNamespaces = [
        '	Module\\Booking\\Shared\\Infrastructure\\Models\\'
    ];

    public function __construct(
        public readonly ChangesRegistrator $changesRegistrator
    ) {}

    public function handle(string $event, $data): void
    {
        if (!Auth::check() || $this->isChangesIgnored($data[0])) {
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

    private function isChangesIgnored($model): bool
    {
        if (in_array($model::class, $this->ignoredModels)) {
            return true;
        }

        foreach ($this->ignoredNamespaces as $ns) {
            if (str_starts_with($model::class, $ns)) {
                return true;
            }
        }

        $changes = $model->getChanges();
        foreach ($this->ignoredAttributes as $key) {
            unset($changes[$key]);
        }

        return empty($changes);
    }
}
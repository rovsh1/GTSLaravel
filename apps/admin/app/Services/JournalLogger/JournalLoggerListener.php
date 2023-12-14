<?php

namespace App\Admin\Services\JournalLogger;

use App\Admin\Services\JournalLogger\Changes\ModelChanged;
use App\Admin\Services\JournalLogger\Changes\ModelCreated;
use App\Admin\Services\JournalLogger\Changes\ModelDeleted;
use Illuminate\Support\Facades\Auth;

class JournalLoggerListener
{
    private const EVENT_CREATED = 1;
    private const EVENT_UPDATED = 2;
    private const EVENT_DELETED = 3;

    private array $ignoredAttributes = [
        'remember_token',
        'updated_at',
        'created_at'
    ];

    private array $ignoredModels = [];

    private array $ignoredNamespaces = [
        'Module\\Booking\\Shared\\Infrastructure\\Models\\'
    ];

    private array $registerEvents = [
        'eloquent.updated' => self::EVENT_UPDATED,
        'eloquent.deleted' => self::EVENT_DELETED,
        'eloquent.created' => self:: EVENT_CREATED
    ];

    public function __construct(
        public readonly ChangesRegistrator $changesRegistrator
    ) {}

    public function handle(string $event, $data): void
    {
        if (!Auth::check() || !($eventId = $this->getEventId($event)) || $this->isChangesIgnored($data[0])) {
            return;
        }

        $changes = match ($eventId) {
            self::EVENT_CREATED => new ModelCreated($data[0]),
            self::EVENT_UPDATED => new ModelChanged($data[0]),
            self::EVENT_DELETED => new ModelDeleted($data[0]),
        };

        $this->changesRegistrator->register($changes);
    }

    private function getEventId(string $event): ?int
    {
        foreach ($this->registerEvents as $prefix => $id) {
            if (str_starts_with($event, $prefix)) {
                return $id;
            }
        }

        return null;
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
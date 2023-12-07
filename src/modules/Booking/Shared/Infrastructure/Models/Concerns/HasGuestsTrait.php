<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Booking\Shared\Infrastructure\Models\Guest;

trait HasGuestsTrait
{
    private static $isBooted = false;

    private array $savingGuestIds;

    protected static function bootGuests(): void
    {
        static::$isBooted = true;
        static::saved(function (self $model) {
            if (isset($model->savingGuestIds)) {
                $model->guests()->sync($model->savingGuestIds);
                unset($model->savingGuestIds);
            }
        });
    }

    public function getFillable(): array
    {
        $this->ensureBooted();
        return [
            ...parent::getFillable(),
            'guestIds',
        ];
    }

    public function guests(): BelongsToMany
    {
        $this->ensureBooted();
        return $this->belongsToMany(
            Guest::class,
            $this->getGuestsTable(),
            $this->getForeignPivotKey(),
            $this->getRelatedPivotKey()
        );
    }

    public function guestIds(): Attribute
    {
        $this->ensureBooted();
        return Attribute::make(
            get: fn() => $this->guests()->pluck('guest_id')->toArray(),
            set: function (array $guestIds) {
                $this->savingGuestIds = $guestIds;

                return [];
            }
        );
    }

    private function ensureBooted(): void
    {
        if (!static::$isBooted) {
            throw new \RuntimeException('Boot guests required before accessing');
        }
    }

    abstract protected function getGuestsTable(): string;

    abstract protected function getForeignPivotKey(): string;

    abstract protected function getRelatedPivotKey(): string;
}

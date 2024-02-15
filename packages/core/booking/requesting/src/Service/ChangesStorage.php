<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service;

use Illuminate\Database\Eloquent\Builder;
use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Pkg\Booking\Requesting\Models\Changes as Model;
use Sdk\Booking\ValueObject\BookingId;

class ChangesStorage implements ChangesStorageInterface
{
    public function find(ChangesIdentifier $identifier): ?Changes
    {
        $model = $this->findModel($identifier);

        return $model ? $this->buildChanges($model) : null;
    }

    public function exists(ChangesIdentifier $identifier): bool
    {
        return (bool)$this->findModel($identifier);
    }

    public function store(Changes $changes): void
    {
        $model = $this->findModel($changes->identifier());
        if ($model) {
            Model::where('booking_id', $changes->bookingId())
                ->where('field', $changes->field())
                ->update([
                    'status' => $changes->status()->name,
                    'description' => $changes->description(),
                    'payload' => $changes->payload(),
                ]);
        } else {
            Model::create([
                'booking_id' => $changes->bookingId(),
                'field' => $changes->field(),
                'status' => $changes->status()->name,
                'description' => $changes->description(),
                'payload' => $changes->payload(),
            ]);
        }
    }

    public function remove(ChangesIdentifier $identifier): void
    {
        Model::where('booking_id', $identifier->bookingId())
            ->where('field', $identifier->field())
            ->delete();
    }

    public function get(BookingId $bookingId, ?string $field): array
    {
        return Model::where('booking_id', $bookingId->value())
            ->where(function (Builder $query) use ($field) {
                if (!empty($field)) {
                    $query->where('field', $field);
                }
            })
            ->get()
            ->map(fn(Model $model) => $this->buildChanges($model))
            ->all();
    }

    public function clear(BookingId $bookingId): void
    {
        Model::where('booking_id', $bookingId->value())
            ->delete();
    }

    private function findModel(ChangesIdentifier $identifier): ?Model
    {
        return Model::where('booking_id', $identifier->bookingId())
            ->where('field', $identifier->field())
            ->first();
    }

    private function buildChanges(Model $model): Changes
    {
        return new Changes(
            new ChangesIdentifier($model->booking_id, $model->field),
            $model->status,
            $model->field,
            $model->payload,
        );
    }
}

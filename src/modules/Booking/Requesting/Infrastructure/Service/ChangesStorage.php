<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Infrastructure\Service;

use Module\Booking\Requesting\Domain\Entity\Changes;
use Module\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Module\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Module\Booking\Requesting\Infrastructure\Models\Changes as Model;
use Sdk\Booking\ValueObject\BookingId;

class ChangesStorage implements ChangesStorageInterface
{
    public function find(ChangesIdentifier $identifier): ?Changes
    {
        $model = $this->findModel($identifier);

        return $model ? new Changes(
            $identifier,
            $model->status,
            $model->field,
            $model->payload,
        ) : null;
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
}

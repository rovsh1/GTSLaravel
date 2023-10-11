<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject\HotelBooking;


use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, RoomBookingId>
 */
class RoomBookingIdCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof RoomBookingId) {
            throw new \InvalidArgumentException(RoomBookingId::class . ' instance required');
        }
    }

    public function has(RoomBookingId $id): bool
    {
        foreach ($this->items as $roomId) {
            if ($roomId->isEqual($id)) {
                return true;
            }
        }

        return false;
    }
}

<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject\HotelBooking;

use Illuminate\Support\Collection;
use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends Collection<int, HotelRoomBooking>
 */
final class RoomBookingCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof HotelRoomBooking) {
            throw new \InvalidArgumentException(HotelRoomBooking::class . ' instance required');
        }
    }
}

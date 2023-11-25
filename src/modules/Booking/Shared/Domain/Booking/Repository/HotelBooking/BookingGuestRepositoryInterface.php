<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\HotelBooking;

use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;

interface BookingGuestRepositoryInterface
{
    public function bind(AccommodationId $accommodationId, GuestId $guestId): void;

    public function unbind(AccommodationId $accommodationId, GuestId $guestId): void;
}

<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Order\Domain\ValueObject\TouristId;

interface BookingTouristRepositoryInterface
{
    public function bind(BookingId $bookingId, TouristId $touristId): void;

    public function unbind(BookingId $bookingId, TouristId $touristId): void;
}

<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\EditorInterface;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;

class DayCarTrip extends AbstractEditor implements EditorInterface
{
    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): ServiceDetailsInterface
    {
        // TODO: Implement create() method.
    }

    public function update(BookingId $bookingId, array $detailsData): void
    {
        // TODO: Implement update() method.
    }
}

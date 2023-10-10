<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory;

use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceId;

interface EditorInterface
{
    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): ServiceDetailsInterface;

    public function update(BookingId $bookingId, array $detailsData): void;
}

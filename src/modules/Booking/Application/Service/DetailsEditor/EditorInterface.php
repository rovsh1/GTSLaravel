<?php

declare(strict_types=1);

namespace Module\Booking\Application\Service\DetailsEditor;

use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;

interface EditorInterface
{
    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): ServiceDetailsInterface;

    public function update(BookingId $bookingId, array $detailsData): void;
}

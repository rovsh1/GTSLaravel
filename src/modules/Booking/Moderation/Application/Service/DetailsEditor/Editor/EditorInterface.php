<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Entity\DetailsInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;

interface EditorInterface
{
    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): DetailsInterface;

    public function update(DetailsInterface $details, array $detailsData): void;
}

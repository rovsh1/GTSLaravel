<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\ServiceId;

interface EditorInterface
{
    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): DetailsInterface;

    public function update(DetailsInterface $details, array $detailsData): void;
}

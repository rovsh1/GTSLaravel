<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

interface OtherServiceFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        ?string $description,
        ?\DateTimeInterface $date
    ): Other;
}

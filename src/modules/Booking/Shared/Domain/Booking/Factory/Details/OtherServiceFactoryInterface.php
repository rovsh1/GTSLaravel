<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use Sdk\Booking\Entity\Details\Other;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\ServiceInfo;

interface OtherServiceFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        ?string $description,
        ?\DateTimeInterface $date
    ): Other;
}

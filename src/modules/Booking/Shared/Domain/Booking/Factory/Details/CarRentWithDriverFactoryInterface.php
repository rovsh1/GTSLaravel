<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

interface CarRentWithDriverFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        ?BookingPeriod $bookingPeriod,
    ): CarRentWithDriver;
}

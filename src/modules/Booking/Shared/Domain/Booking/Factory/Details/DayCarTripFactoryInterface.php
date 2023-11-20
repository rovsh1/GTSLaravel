<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

interface DayCarTripFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        CarBidCollection $carBids,
        ?string $destinationsDescription,
        ?DateTimeInterface $departureDate,
    ): DayCarTrip;
}

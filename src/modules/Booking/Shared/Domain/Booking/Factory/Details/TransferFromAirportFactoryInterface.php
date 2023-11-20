<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

interface TransferFromAirportFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        CarBidCollection $carBids,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport;
}

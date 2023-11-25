<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Sdk\Booking\Entity\BookingDetails\TransferToAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

interface TransferToAirportFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        CarBidCollection $carBids,
        ?string $flightNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToAirport;
}

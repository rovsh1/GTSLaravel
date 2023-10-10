<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;

interface TransferFromAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferFromAirport;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport;
}

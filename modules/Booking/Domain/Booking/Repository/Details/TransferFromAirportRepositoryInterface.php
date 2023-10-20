<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\Booking\Entity\TransferFromAirport;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;

interface TransferFromAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferFromAirport;

    public function findOrFail(BookingId $bookingId): TransferFromAirport;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        CarBidCollection $carBids,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport;

    public function store(TransferFromAirport $details): bool;
}

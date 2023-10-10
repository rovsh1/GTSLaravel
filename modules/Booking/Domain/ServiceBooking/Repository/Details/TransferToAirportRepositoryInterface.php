<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;

interface TransferToAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferToAirport;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $departureDate,
    ): TransferToAirport;

    public function store(TransferToAirport $details): bool;
}

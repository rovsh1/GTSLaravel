<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;

interface TransferToRailwayRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferToRailway;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $railwayStationId,
        CarBidCollection $carBids,
        ?string $trainNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToRailway;

    public function store(TransferToRailway $details): bool;
}

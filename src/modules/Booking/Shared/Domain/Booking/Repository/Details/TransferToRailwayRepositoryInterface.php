<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

interface TransferToRailwayRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferToRailway;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $railwayStationId,
        int $cityId,
        CarBidCollection $carBids,
        ?string $trainNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToRailway;

    public function store(TransferToRailway $details): bool;
}

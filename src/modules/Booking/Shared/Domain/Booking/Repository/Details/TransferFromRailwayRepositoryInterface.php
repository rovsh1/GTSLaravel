<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferFromRailway;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

interface TransferFromRailwayRepositoryInterface
{
    public function find(BookingId $bookingId): ?TransferFromRailway;

    public function findOrFail(BookingId $bookingId): TransferFromRailway;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $railwayStationId,
        int $cityId,
        CarBidCollection $carBids,
        ?string $trainNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromRailway;

    public function store(TransferFromRailway $details): bool;
}

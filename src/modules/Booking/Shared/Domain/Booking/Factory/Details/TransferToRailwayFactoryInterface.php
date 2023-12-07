<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Sdk\Booking\Entity\Details\TransferToRailway;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

interface TransferToRailwayFactoryInterface
{
    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $railwayStationId,
        int $cityId,
        ?string $trainNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToRailway;
}

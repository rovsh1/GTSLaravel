<?php

namespace Module\Booking\Deprecated\AirportBooking\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Deprecated\AirportBooking\AirportBooking;
use Module\Booking\Deprecated\AirportBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?AirportBooking;

    public function findOrFail(BookingId $id): AirportBooking;

    public function get(): Collection;

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        int $serviceId,
        int $airportId,
        CarbonInterface $date,
        BookingPrices $price,
        AdditionalInfo $additionalInfo,
        CancelConditions $cancelConditions,
        ?string $note = null
    ): AirportBooking;

    public function store(BookingInterface|AirportBooking $booking): bool;

    public function delete(BookingInterface|AirportBooking $booking): void;
}
